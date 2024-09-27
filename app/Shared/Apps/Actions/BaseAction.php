<?php

namespace App\Shared\Apps\Actions; 

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

abstract class BaseAction implements ActionInterface
{
    use JsonDecoder;
  

    // Define the prepareRequest method
   
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['verify' => false]); // Disable SSL verification if needed
    }

    // This method will replace getUri, getRequestBody, and getHeaders
    abstract protected function prepareRequest(array $inputData, array $configData): array;

   // Define a protected method to be overridden by subclasses
   protected function setFields(): array
   {
       // Default fields setup with empty values
       return [
           'inputRequiredField' => [],
           'inputNonRequiredField' => [],
           'configRequiredField' => []
       ];
   }

   // Method to get fields based on type
   public function getFieldByType(string $type): array
   {
       $fields = $this->setFields();
       return $fields[$type] ?? [];
   }

    // This method can be overridden by subclasses to specify which keys should be JSON decoded
    protected function getJsonKeys(): array
    {
        return []; // Default empty array, meaning no JSON decoding by default
    }

    // This method preprocesses the input data by decoding JSON strings based on the specified keys
    protected function preprocessInputData(array $inputData): array
    {
        $jsonKeys = $this->getJsonKeys();
        if (!empty($jsonKeys)) {
            $inputData = $this->decodeJsonStrings($inputData, $jsonKeys);
            Log::info(get_class($this) . "::execute() inputData after modification: " . json_encode($inputData));
        }
        return $inputData;
    }

    public function execute(array $inputData, $configData): array
    {
        // Log Execution
        Log::info(get_class($this) . "::execute() Receive input data: " . json_encode($inputData));
        Log::info(get_class($this) . "::execute() Receive configData data: " . json_encode($configData));

        try {
            // Validate input data
            $validatedIncommingData= $this->validateData($inputData, $this->getFieldByType('inputRequiredField'),$this->getFieldByType('inputNonRequiredField'));
            $validatedIncommingData = $this->preprocessInputData($validatedIncommingData);

            // Convert configData to array if it's not already an array
            if ($configData instanceof \Illuminate\Support\Collection) {
                $configData = $configData->toArray();
            }

            // Validate config data
            $validatedConfigData= $this->validateData($configData, $this->getFieldByType('configRequiredField'));

            // Perform the action
            return [
                'status' => 'success',
                'response' => $this->performAction($validatedIncommingData,$validatedConfigData)
            ];
        } catch (\Exception $e) {
            // Log Error
            Log::error(get_class($this) . '::execute() Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    protected function validateData(array $data, array $requiredFields, array $nonRequiredFields = []): array
    {
        $missingFields = [];

        // Check for missing required fields
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                 $missingFields[] = $field;
            }
        }

         // If there are missing fields, throw an exception with all missing fields
        if (!empty($missingFields)) {
            $missingFieldsList = implode(', ', $missingFields);
            throw new \Exception("Missing required fields: $missingFieldsList");
        }
    
        // Ensure non-required fields have default values
        foreach ($nonRequiredFields as $field => $defaultValue) {
            $data[$field] = $data[$field] ?? $defaultValue;
        }
    
        return $data;
    }
    

    protected function performAction(array $inputData,array $configData): array
    {
        $request = $this->prepareRequest($inputData,$configData);

        // Extract necessary parts from the prepared request
        $url = $request['url'] ?? '';
        $headers = $request['headers'] ?? [];
        $body = $request['body'] ?? null;
        $method = $request['method'] ?? 'POST';
        $bodyType = $request['bodyType'] ?? 'json';
        $queryParams = $request['queryParams'] ?? [];

        $options = $this->prepareRequestOptions($headers, $body, $bodyType, $queryParams);

        return $this->sendRequest($method, $url, $options);
    }

    protected function prepareRequestOptions(
        array $headers = [],
        $body = null,
        string $bodyType = 'json',
        array $queryParams = []
    ): array {
        $options = [
            'headers' => $headers,
            'query' => $queryParams,
        ];

        switch ($bodyType) {
            case 'json':
                $options['json'] = $body;
                break;
            case 'form_params':
                $options['form_params'] = $body;
                break;
            case 'multipart':
                $options['multipart'] = $this->prepareMultipartBody($body);
                break;
            case 'body':
                $options['body'] = $body;
                break;
            case 'binary':
                $options['body'] = fopen($body, 'r');
                break;
            case 'graphql':
                $options['body'] = json_encode(['query' => $body]);
                $options['headers']['Content-Type'] = 'application/json';
                break;
            default:
                throw new \InvalidArgumentException("Unsupported body type: {$bodyType}");
        }

        return $options;
    }

    private function prepareMultipartBody(array $body): array
    {
        return array_map(function ($name, $content) {
            return is_array($content) && isset($content['contents'])
                ? $content
                : ['name' => $name, 'contents' => $content];
        }, array_keys($body), $body);
    }

    protected function sendRequest(string $method, string $url, array $options = []): array
    {
        // Log request details
        Log::info("Request to {$method} {$url}", $options);

        try {
            $response = $this->client->request($method, $url, $options);
            $body = json_decode($response->getBody(), true);

            // Log successful response
            Log::info('API Response: ', $body);

            return $body;
        } catch (RequestException $e) {
            $errorBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'No status code';

            // Log error
            Log::error("API Error: {$e->getMessage()}", [
                'status_code' => $statusCode,
                'response_body' => $errorBody,
            ]);

            throw new \Exception($errorBody);
        }
    }
}
