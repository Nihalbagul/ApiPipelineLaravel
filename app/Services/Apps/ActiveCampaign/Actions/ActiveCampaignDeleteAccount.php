<?php

namespace App\Services\Apps\ActiveCampaign\Actions;

use App\Shared\Apps\Actions\BaseAction;

class ActiveCampaignDeleteAccount extends BaseAction
{
    /**
     * Prepare the request with necessary headers, URL, and body.
     */
    protected function prepareRequest(array $inputData, array $configData): array
    {
        // Endpoint URL for deleting an account (replace {accountId} with dynamic input)
        $url = "https://nihalbagul08120506.activehosted.com/api/3/accounts/" . $inputData['accountId'];



        // Set headers for the API request
        $headers = [
            'Api-Token' => $configData['apiToken'],
            'Content-Type' => 'application/json',
        ];

        // Return the request structure
        return [
            'url' => $url,
            'method' => 'DELETE',
            'headers' => $headers,
            'bodyType' => 'json', // DELETE requests typically don't have a body
        ];
    }

    /**
     * Validate the required fields.
     */
    protected function setFields(): array
    {
        return [
            'inputRequiredField' => ['accountId'], // The ID of the account to be deleted
            'inputNonRequiredField' => [],
            'configRequiredField' => ['apiToken'], // Api-Token is required
        ];
    }

    /**
     * Handle specific error cases for the API (optional override).
     */
    protected function handleApiResponseError($response): array
    {
        // Custom error handling for ActiveCampaign API if needed
        return parent::handleApiResponseError($response);
    }
}
