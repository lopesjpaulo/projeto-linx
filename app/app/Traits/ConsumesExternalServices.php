<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalServices
{
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        try {
            $client = new Client([
                'base_uri' => $this->baseUri
            ]);
    
            if (isset($this->secret)) {
                $headers['Authorization'] = $this->secret;
            }
    
            $response = $client->request($method, $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);
    
            return \json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            return explode("\n", $th->getResponse()->getBOdy()->getContents());
        }
    }
}
