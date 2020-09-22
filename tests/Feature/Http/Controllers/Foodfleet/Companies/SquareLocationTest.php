<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Companies;

use App\User;
use FreshinUp\FreshBusForms\Models\Company\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquareLocationTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $this->markTestSkipped();
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        factory(User::class, 2)->create();

        $company = factory(Company::class)->create();
        $users = factory(User::class, 11)->create([
            'company_id' => $company->id
        ]);

        // TODO: this should be mocked
        $api_config = new \SquareConnect\Configuration();
        $api_config->setHost(config('services.square.sq_domain'));
        $api_config->setAccessToken(config('services.square.sq_token'));
        $api_client = new \SquareConnect\ApiClient($api_config);
        # create an instance of the Location API
        $locations_api = new \SquareConnect\Api\LocationsApi($api_client);
        try {
            $locations = $locations_api->listLocations();
            $data = array();
            foreach ($locations->getLocations() as $idx => $location) {
                $data[] = ['square_id' => $location->getId(), 'name' => $location->getName()];
            }

            $response = $this->json('get', "/api/foodfleet/companies/{$company->id}/square-locations");
            $response = json_decode($response->content());
            $this->assertNotEmpty($data);
            $this->assertEquals(count($response->data), count($data));
            foreach ($response->data as $idx => $location) {
                $this->assertArraySubset([
                    'square_id' => $location->square_id,
                    'name' => $location->name,
                ], $data[$idx]);
            }
        } catch (\SquareConnect\ApiException $e) {
        }
    }
}
