<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;


class Squares extends Controller
{
    /**
     * Check authorization code from Square
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(Request $request)
    {
        $code = $request->input('code');

        try {
            $this->getOAuthToken($code);
        } catch (\Exception $e) {
            return response()->json(['result' => false], SymfonyResponse::HTTP_BAD_REQUEST);
        }
        return response()->json(['result' => true], SymfonyResponse::HTTP_CREATED);
    }


    function getOAuthToken($authorizationCode) {

        // Create an OAuth API client
        $oauthApi = new \SquareConnect\Api\OAuthApi();
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

        // Return both the access token and refresh token
        return array($accessToken, $refreshToken);
    }

}
