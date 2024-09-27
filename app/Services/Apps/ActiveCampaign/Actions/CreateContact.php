<?php


namespace App\Services\Apps\ActiveCampaign\Actions;

use App\Shared\Apps\Actions\BaseAction;
use Illuminate\Support\Facades\Http;

class CreateContact extends BaseAction
{
    protected function prepareRequest(array $inputData, array $configData): array
    {
        $url = 'https://nihalbagul08120506.activehosted.com/api/3/contacts';

        $headers = [
            'Api-Token' => $configData['apiToken'],
            'Content-Type' => 'application/json',
        ];

        $body = [
            'contact' => [
                'email' => $inputData['email'],
                'firstName' => $inputData['firstName'],
                'lastName' => $inputData['lastName'],
                'phone' => $inputData['phone'],
            ],
        ];

        return [
            'url' => $url,
            'method' => 'POST',
            'headers' => $headers,
            'bodyType' => 'json',
            'body' => $body,
        ];
    }

    protected function setFields(): array
    {
        return [
            'inputRequiredField' => ['email', 'firstName', 'lastName', 'phone'],
            'inputNonRequiredField' => [],
            'configRequiredField' => ['apiToken'],
        ];
    }

    protected function getJsonKeys(): array
    {
        return ['contact'];
    }

    protected function handleApiCall(array $request): array
    {
        $response = Http::withHeaders($request['headers'])->post($request['url'], $request['body']);
        $this->logApiResponse($response);

        if ($response->failed()) {
            throw new \Exception('API Call Failed: ' . $response->body());
        }

        return $response->json();
    }

    protected function logApiResponse($response): void
    {
        \Log::info('API Response:', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
    }
}
