<?php

namespace App\Helpers;

class SquareHelper
{
    /**
     * Return an istance of Square Api Client
     *
     * @param String|null $accessToken
     * @return \SquareConnect\ApiClient
     */
    public static function getApiClient($accessToken = null)
    {
        if (!$accessToken) {
            $accessToken = config('square.access_token');
        }

        // Create and configure a new API client object
        $apiConfig = new \SquareConnect\Configuration();
        $apiConfig->setAccessToken($accessToken);
        $apiConfig->setHost(config('square.domain'));
        return new \SquareConnect\ApiClient($apiConfig);
    }
}
