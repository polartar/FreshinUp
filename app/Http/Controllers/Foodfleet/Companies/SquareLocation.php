<?php


namespace App\Http\Controllers\Foodfleet\Companies;

use App\User;
use App\Models\Foodfleet\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Company\SquareLocation as SquareLocationResource;
use Illuminate\Support\Facades\DB;

class SquareLocation extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $id)
    {
# setup authorization
        $api_config = new \SquareConnect\Configuration();
        $api_config->setHost(config('services.square.sq_domain'));
        $api_config->setAccessToken(config('services.square.sq_token'));
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
        }
        // $data = SquareLocationResource::collection($data);

        $dataobj = array();
        $dataobj['data'] = $data;
        return json_encode($dataobj);
    }
}
