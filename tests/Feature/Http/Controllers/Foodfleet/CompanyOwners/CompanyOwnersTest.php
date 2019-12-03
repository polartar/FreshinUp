<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\User;
use FreshinUp\FreshBusForms\Models\Company\Company;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyOwnersTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetList()
    {
        $user1 = factory(User::class)->create([ 'first_name' => 'User1' ]);
        factory(Company::class)->create([ 'users_id' => $user1->id ]);

        Passport::actingAs($user1);

        $user2 = factory(User::class)->create([ 'first_name' => 'User2' ]);
        $documentTypes = factory(Company::class)->create([ 'users_id' => $user2->id ]);
        
        $user3 = factory(User::class)->create([ 'first_name' => 'User3' ]);

        $data = $this
            ->json('get', "/api/foodfleet/company-owners")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertArraySubset([['first_name' => 'User1'], ['first_name' => 'User2']], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithFilters()
    {
        $user1 = factory(User::class)->create([ 'first_name' => 'User1' ]);
        factory(Company::class)->create([ 'users_id' => $user1->id ]);

        Passport::actingAs($user1);

        $user2 = factory(User::class)->create([ 'first_name' => 'User2' ]);
        $documentTypes = factory(Company::class)->create([ 'users_id' => $user2->id ]);
        
        $user3 = factory(User::class)->create([ 'first_name' => 'User3' ]);


        $data = $this
            ->json('get', "/api/foodfleet/company-owners?filter[first_name]=2")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([['first_name' => 'User2']], $data);
    }
}
