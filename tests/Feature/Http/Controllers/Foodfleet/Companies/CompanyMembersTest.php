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

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithPagination()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $company = factory(Company::class)->create();
        factory(User::class, 2)->create([
            'company_id' => $company->id
        ]);

        $company = factory(Company::class)->create();
        $users = factory(User::class, 11)->create([
            'company_id' => $company->id
        ]);

        $response = $this->json('get', "/api/foodfleet/companies/{$company->id}/members?page[size]=5");
        
        $response->assertStatus(200);
        $data = $response->json('data');
        $meta = $response->json('meta');

        $this->assertCount(5, $data);
        $this->assertEquals(5, $meta['per_page']);
        $this->assertEquals(11, $meta['total']);
        $this->assertEquals(3, $meta['last_page']);
        $this->assertEquals(1, $meta['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $company = factory(Company::class)->create();

        factory(User::class, 2)->create([
            'first_name' => 'Not visibles',
            'company_id' => $company->id
        ]);

        $usersToFind = factory(User::class, 5)->create([
            'first_name' => 'To find',
            'company_id' => $company->id
        ]);


        $data = $this
            ->json('get', "/api/foodfleet/companies/{$company->id}/members?filter[first_name]=find")
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
    }
}
