<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Squares;

use App\Models\Foodfleet\Store;
use App\User;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Square\SquareClient;
use Tests\TestCase;

class SquaresTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    private function mockSquare($isSuccess)
    {
        $accessToken = $this->faker->uuid;
        $refreshToken = $this->faker->uuid;
        $resultMock = \Mockery::mock();
        $resultMock->shouldReceive('getAccessToken')
            ->andReturn($accessToken);
        $resultMock->shouldReceive('getRefreshToken')
            ->andReturn($refreshToken);
        $apiResponseMock = \Mockery::mock(\Square\Http\ApiResponse::class);
        $apiResponseMock->shouldReceive('isSuccess')
            ->andReturn($isSuccess);
        $apiResponseMock->shouldReceive('getResult')
            ->andReturn($resultMock);
        $oAuthApiMock = \Mockery::mock(\Square\Apis\OAuthApi::class);
        $oAuthApiMock->shouldReceive('obtainToken')
            ->andReturn($apiResponseMock);
        $this->mock(SquareClient::class)
            ->shouldReceive('getOAuthApi')
            ->andReturn($oAuthApiMock);
        if (!$isSuccess) {
            $apiResponseMock->shouldReceive('getErrors');
        }
        return compact('accessToken', 'refreshToken');
    }

    public function testVerifyWithNotValidToken()
    {
        $store = factory(Store::class)->create();
        $user = factory(User::class)->create([
            'level' => 1,
        ]);

        Passport::actingAs($user);
        $this->mockSquare(false);
        $response = $this->json('POST', "/api/foodfleet/squares/authorize", [
            'code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q',
            'store_uuid' => $store->uuid
        ]);
        $response->assertStatus(400);
    }

    public function testVerifyWithNoTokenInTheRequest()
    {
        $user = factory(User::class)->create([
            'level' => 1,
        ]);

        Passport::actingAs($user);
        $errors = $this->json('post', "/api/foodfleet/squares/authorize")
            ->assertStatus(422)
            ->json('errors');
        $this->assertArrayHasKey('code', $errors);
        $this->assertArrayHasKey('store_uuid', $errors);
    }

    public function testVerifyWithUserThatItIsNotAnAdmin()
    {
        $store = factory(Store::class)->create();
        $user = factory(User::class)->create([
            'level' => 8,
        ]);

        Passport::actingAs($user);

        $this->json('post', "/api/foodfleet/squares/authorize", [
            'code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q',
            'store_uuid' => $store->uuid
        ])
            ->assertStatus(403);
    }

    public function testVerifyOk()
    {
        $store = factory(Store::class)->create([
            'square_access_token' => null,
            'square_refresh_token' => null,
        ]);
        $user = factory(User::class)->create([
            'level' => 1,
        ]);

        $result = $this->mockSquare(true);
        Passport::actingAs($user);

        $this->assertNull($store->square_access_token);
        $this->assertNull($store->square_refresh_token);
        $response = $this->json('POST', "/api/foodfleet/squares/authorize", [
            'code' => 'sandbox-sq0cgb-XZFtOnjgZe4Dt5XZGbs93Q',
            'store_uuid' => $store->uuid
        ]);
        $response->assertStatus(200);
        $store->refresh();
        $this->assertEquals($result['accessToken'], $store->square_access_token);
        $this->assertEquals($result['refreshToken'], $store->square_refresh_token);
    }
}
