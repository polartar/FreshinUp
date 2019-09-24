<?php

namespace Tests\Feature\Unit\Helpers\SquareHelper;

use App\Helpers\SquareHelper;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Item;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use SquareConnect\ApiClient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquareHelperTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAccessTokenPassed()
    {
        $apiClient = SquareHelper::getApiClient('test');

        $this->assertEquals('test', $apiClient->getConfig()->getAccessToken());
        $this->assertInstanceOf(ApiClient::class, $apiClient);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAccessTokenNotPassed()
    {
        $apiClient = SquareHelper::getApiClient();

        $this->assertEquals(
            'EAAAEO4a3YaMEUyfLWMJA9EMPp5ZdIr2nUliMCG28hAAjcNTfooTw0NVC-EOT36d',
            $apiClient->getConfig()->getAccessToken()
        );
        $this->assertInstanceOf(ApiClient::class, $apiClient);
    }
}
