<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use App\User;
use FreshinUp\FreshBusForms\Actions\Action;

class AssociateSquareTokens implements Action
{
    public function execute(array $data)
    {
        $authorizationCode = $data['code'];

        // Create and configure a new API client object
        // TODO: put in the container for better testability
        $apiClient = SquareHelper::getApiClient();

        // Create an OAuth API client
        $oauthApi = new \SquareConnect\Api\OAuthApi();
        $oauthApi->setApiClient($apiClient);
        $body = new \SquareConnect\Model\ObtainTokenRequest();

        // Set the POST body
        $body->setClientId(config('square.application_id'));
        $body->setClientSecret(config('square.app_secret'));
        $body->setGrantType("authorization_code");
        $body->setCode($authorizationCode);

        try {
            $result = $oauthApi->obtainToken($body);
        } catch (\Exception $e) {
            throw new \Exception("Error Processing Request: Token exchange failed!", 1);
        }

        $accessToken = $result->getAccessToken();
        $refreshToken = $result->getRefreshToken();

        $user = User::findOrFail($data['user_id']);
        $company = $user->company;

        $company->square_access_token = $accessToken;
        $company->square_refresh_token = $refreshToken;

        $company->save();
    }
}
