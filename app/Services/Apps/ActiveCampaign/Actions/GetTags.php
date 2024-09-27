<?php

namespace App\Services\Apps\ActiveCampaign\Actions;

use App\Shared\Apps\Actions\BaseAction;

class GetTags extends BaseAction
{
    protected function prepareRequest(array $inputData, array $configData): array
    {
        $url = 'https://nihalbagul08120506.activehosted.com/api/3/tags';
        
        $headers = [
            'Api-Token' => $configData['apiToken'],
            'Content-Type' => 'application/json',
        ];

        return [
            'url' => $url,
            'method' => 'GET',
            'headers' => $headers,
        ];
    }

    protected function setFields(): array
    {
        return [
            'configRequiredField' => ['apiToken'],
            'inputRequiredField' => [], // No required input data for this request
        ];
    }
}
