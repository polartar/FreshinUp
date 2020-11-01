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
            'code' => 'required'
        ]);
        /** @var User $authUser */
        $authUser = $request->user();
        if (!$authUser || !$authUser->isAdmin() || $authUser->company == null) {
            throw new AuthorizationException();
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

        $user = $request->user();
        if (!$user) {
            throw new AuthenticationException();
        }
        $company = $user->company;

        $company->square_access_token = $result->getAccessToken();
        $company->square_refresh_token = $result->getRefreshToken();
        // TODO: save this info $result->getExpiresAt() so that we can refresh the token
        // after expiration in 30 days
        $company->save();
        return response()->json(null);
    }


    /**
     * @param  Request  $request
     * @param  Company  $company
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResource
     * @throws \Exception
     */
    public function locations(Request $request, Company $company)
    {
        if (!$company->square_access_token) {
            return response()->json([
                'message' => "Square not setup yet."
            ])->setStatusCode(400);
        }
        $client = new SquareClient([
            'accessToken' => $company->square_access_token,
            'environment' => config('services.square.environment'),
        ]);
        try {
            $locationsApi = $client->getLocationsApi();
            $apiResponse = $locationsApi->listLocations();
            if (!$apiResponse->isSuccess()) {
                return (new JsonResource($apiResponse->getErrors()))
                    ->toResponse($request)
                    ->setStatusCode(400);
            }

            // expected output []{ square_id: string, name: string }
            $listLocationsResponse = $apiResponse->getResult();
            $locations = $listLocationsResponse->getLocations();

            // name: string, // business name
            // id: string, // location id
            return new JsonResource($locations);
        } catch (ApiException $e) {
            throw new \Exception("Received error while calling Square: " . $e->getMessage());
        }
    }
}
