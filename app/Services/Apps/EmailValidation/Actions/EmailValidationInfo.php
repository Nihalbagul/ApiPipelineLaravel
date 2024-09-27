<?php

namespace App\Services\Apps\EmailValidation\Actions;

use App\Shared\Apps\Actions\BaseAction;
use Illuminate\Support\Facades\Http;

class EmailValidationInfo extends BaseAction
{
    protected function prepareRequest(array $inputData, array $configData): array
    {
        $url = 'https://api.emailvalidation.io/v1/info';

        $queryParams = [
            'apikey' => $configData['apiKey'],
            'email' => $inputData['email']
        ];

        return [
            'url' => $url,
            'method' => 'GET',
            'queryParams' => $queryParams,
            'bodyType' => null,
            'body' => null,
        ];
    }

    protected function setFields(): array
    {
        return [
            'inputRequiredField' => ['email'],
            'inputNonRequiredField' => [],
            'configRequiredField' => ['apiKey']
        ];
    }

    protected function getJsonKeys(): array
    {
        return []; // Specify any JSON keys if necessary
    }

    protected function executeAction(array $inputData, array $configData)
    {
        $requestData = $this->prepareRequest($inputData, $configData);
        $response = Http::withQueryParameters($requestData['queryParams'])
            ->get($requestData['url']);

        if ($response->failed()) {
            // Log error and return appropriate response
            $this->logError($response->body());
            return response()->json(['error' => 'Email validation failed.'], 400);
        }

        // Log successful response
        $this->logSuccess($response->body());
        return response()->json($response->json());
    }
}
