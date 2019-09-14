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

    public function testPostWithNotValidToken()
    {
        $user = factory(User::class)->create([
            'level' => 1,
        ]);
        $company = factory(Company::class)->create();
        $user->company()->associate($company);
        $user->save();

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares", ['code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q']);
        $response->assertStatus(400);
    }

    public function testPostWithNoTokenInTheRequest()
    {
        $user = factory(User::class)->create([
            'level' => 1,
        ]);
        $company = factory(Company::class)->create();
        $user->company()->associate($company);
        $user->save();

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares");
        $response->assertStatus(422);
    }

    public function testPostWithUserThatItIsNotAnAdmin()
    {
        $user = factory(User::class)->create(['level' => 8]);
        $company = factory(Company::class)->create();
        $user->company()->associate($company);
        $user->save();

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares", ['code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q']);
        $response->assertStatus(403);
    }

    public function testPostWithUserThatDoesNotHaveACompany()
    {
        $user = factory(User::class)->create([
            'level' => 1,
        ]);

        Passport::actingAs($user);

        $response = $this->json('post', "/api/foodfleet/squares", ['code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q']);
        $response->assertStatus(403);
    }
}
