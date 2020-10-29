<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Squares;

use App\User;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquaresTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testVerifyWithNotValidToken()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'level' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('POST', "/api/foodfleet/squares/authorize", [
            'code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q'
        ]);
        $response->assertStatus(400);
    }

    public function testVerifyWithNoTokenInTheRequest()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'level' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($user);
        $response = $this->json('post', "/api/foodfleet/squares/authorize");
        $response->assertStatus(422);
    }

    public function testVerifyWithUserThatItIsNotAnAdmin()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'level' => 8,
            'company_id' => $company->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares/authorize", [
            'code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q'
        ]);
        $response->assertStatus(403);
    }

    public function testVerifyWithUserThatDoesNotHaveACompany()
    {
        $user = factory(User::class)->create([
            'level' => 1,
            'company_id' => null
        ]);

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares/authorize", ['code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q']);
        $response->assertStatus(403);
    }

    public function testVerifyOk () {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'level' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares/authorize", ['code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q']);
        $response->assertStatus(200);
    }
}
