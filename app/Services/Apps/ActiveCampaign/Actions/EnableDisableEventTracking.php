<?php

namespace App\Services\Apps\ActiveCampaign\Actions;

use App\Shared\Apps\Actions\BaseAction;

class EnableDisableEventTracking extends BaseAction
{
    protected function prepareRequest(array $inputData, array $configData): array
    {
        $url = 'https://nihalbagul08120506.activehosted.com/api/3/eventTracking';

        $headers = [
            'Content-Type' => 'application/json',
            'Api-Token' => $configData['apiToken'] // Using apiToken from the config data
        ];

        $body = [
            'eventTracking' => [
                'enabled' => $inputData['eventTracking']['enabled'] // Use the correct path
            ]
        ];

        return [
            'url' => $url,
            'method' => 'PUT',
            'headers' => $headers,
            'bodyType' => 'json',
            'body' => $body,
        ];
    }

    protected function setFields(): array
    {
        return [
            'inputRequiredField' => ['eventTracking'],
            'configRequiredField' => [ 'apiToken'] // Ensure apiKey is included
        ];
    }

    protected function getJsonKeys(): array
    {
        return ['eventTracking'];
    }

    protected function handleResponse($response)
    {
        if (isset($response['error'])) {
            $this->logError($response['error']); // Log error if present
            throw new \Exception("API Error: " . $response['error']);
        }

        // Log successful response
        $this->logSuccess($response);
        return $response;
    }
}
