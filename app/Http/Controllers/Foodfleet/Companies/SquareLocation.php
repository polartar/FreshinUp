<?php


namespace App\Http\Controllers\Foodfleet\Companies;

use App\Models\Foodfleet\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SquareLocation extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param $company
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Company $company)
    {
        # setup authorization
        $api_config = new \SquareConnect\Configuration();
        $api_config->setHost(config('services.square.sq_domain'));
        // square_access_token,  square_refresh_token
        $api_config->setAccessToken($company->square_access_token);
        // $api_config->setAccessToken(config('services.square.sq_token'));
        $api_client = new \SquareConnect\ApiClient($api_config);

        # create an instance of the Location API
        $locations_api = new \SquareConnect\Api\LocationsApi($api_client);
        $data = array();
        try {
            $locations = $locations_api->listLocations();
            foreach ($locations->getLocations() as $idx => $location) {
                $data[] = ['square_id' => $location->getId(), 'name' => $location->getName()];
            }
        } catch (\SquareConnect\ApiException $e) {
            throw $e;
        }
        $dataobj = array();
        $dataobj['data'] = $data;
        // TODO: return Resource
        return json_encode($dataobj);
    }
}
