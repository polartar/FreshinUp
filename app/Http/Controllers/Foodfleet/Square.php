<?php


namespace App\Http\Controllers\Foodfleet;

use App\Actions\AssociateSquareTokens;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSquaresRequest;
use App\Models\Foodfleet\Company;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Square\Environment;
use Square\Exceptions\ApiException;
use Square\Models\ObtainTokenRequest;
use Square\SquareClient;

class Square extends Controller
{
    public function connect()
    {
        // The same logic is being done on the backend. Now we need to pick where is the best place
        // to put this
        $environment = config('services.square.environment');
        $baseUrl = ($environment === Environment::PRODUCTION)
            ? 'https://connect.squareup.com'
            : 'https://connect.squareupsandbox.com';
        $url = "$baseUrl/oauth2/authorize?"
            . 'scope=CUSTOMERS_WRITE+CUSTOMERS_READ+MERCHANT_PROFILE_READ';
        redirect($url);
    }


    public function authorizeApp(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'store_uuid' => 'required|exists:stores,uuid'
        ]);
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json([
                'message' => 'Not authenticated'
            ], 401);
        }
        /** @var User $authUser */
        if (!$authUser->isAdmin()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 403);
        }
        /** @var SquareClient $client */
        $client = app(SquareClient::class);
        $oAuthApi = $client->getOAuthApi();
        $body = new ObtainTokenRequest(
            config('services.square.app_id'),
            config('services.square.app_secret'),
            'authorization_code'
        );
        $body->setCode($request->input('code'));
        $apiResponse = $oAuthApi->obtainToken($body);
        if (!$apiResponse->isSuccess()) {
            return (new JsonResource($apiResponse->getErrors()))
                ->toResponse($request)
                ->setStatusCode(400);
        }
        $result = $apiResponse->getResult();

        $store = \App\Models\Foodfleet\Store::where('uuid', $request->input('store_uuid'))
            ->firstOrFail();

        $store->square_access_token = $result->getAccessToken();
        $store->square_refresh_token = $result->getRefreshToken();
        // TODO: save this info $result->getExpiresAt() so that we can refresh the token
        // after expiration in 30 days
        $store->save();
        return response()->json(null);
    }
}
