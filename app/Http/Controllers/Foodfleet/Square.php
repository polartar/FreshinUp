<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Square extends Controller
{
    /**
     * Check authorization code from Square
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function checkAuthorization(Request $request)
    {
        // Extract the returned authorization code from the URL
        dd($request->input());
        $authorizationResponse = [];
        $authorizationCode = $authorizationResponse['code'];

        # If there is no authorization code, log the error and throw an exception
        if (!$authorizationCode) {
            error_log('Authorization failed!');
            throw new \Exception("Error Processing Request: Authorization failed!", 1);
        }

        return $authorizationCode ;
    }
}
