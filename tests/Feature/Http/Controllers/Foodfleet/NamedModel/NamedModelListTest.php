<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\NamedModel;

use App\Models\Foodfleet\Device;
use App\Models\Foodfleet\PaymentType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class NamedModelListTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function itRetrievesModelListInTheCorrectFormat()
    {
        $this->listTest(PaymentType::class, 'payment-types');
        $this->listTest(Device::class, 'devices');
    }

    /**
     * @test
     */
    public function itReturnsTheCorrectRecordsWhenFilteringByName()
    {
        $this->filterTest(PaymentType::class, 'payment-types');
        $this->filterTest(Device::class, 'devices');
    }

    public function listTest(string $modelClass, string $endpoint)
    {
        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint");

        // Assert
        $response
            ->assertOk()
            ->assertExactJson(['data' => []]);

        // ============================

        // Create
        $m1 = factory($modelClass)->create(['name' => 'aaaa']);
        $m2 = factory($modelClass)->create(['name' => 'bbbb']);
        $m3 = factory($modelClass)->create(['name' => 'aabb']);

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint");

        // Assert
        $response
            ->assertOk()
            ->assertJson(['data' => [
                ['uuid' => $m1->uuid, 'label' => 'aaaa'],
                ['uuid' => $m2->uuid, 'label' => 'bbbb'],
                ['uuid' => $m3->uuid, 'label' => 'aabb']
            ]]);
    }

    public function filterTest(string $modelClass, string $endpoint)
    {
        // Create
        $m1 = factory($modelClass)->create(['name' => 'aaaa']);
        $m2 = factory($modelClass)->create(['name' => 'bbbb']);
        $m3 = factory($modelClass)->create(['name' => 'aabb']);

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint?filter[name]=aaaa");

        // Assert
        $response
            ->assertOk()
            ->assertJson(['data' => [
                ['uuid' => $m1->uuid, 'label' => 'aaaa']
            ]]);

        // ================

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint?filter[name]=aa");

        // Assert
        $response
            ->assertOk()
            ->assertJson(['data' => [
                ['uuid' => $m1->uuid, 'label' => 'aaaa'],
                ['uuid' => $m3->uuid, 'label' => 'aabb']
            ]]);

        // ================

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint?filter[name]=bbbb");

        // Assert
        $response
            ->assertOk()
            ->assertJson(['data' => [
                ['uuid' => $m2->uuid, 'label' => 'bbbb']
            ]]);

        // ================

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint?filter[name]=bb");

        // Assert
        $response
            ->assertOk()
            ->assertJson(['data' => [
                ['uuid' => $m2->uuid, 'label' => 'bbbb'],
                ['uuid' => $m3->uuid, 'label' => 'aabb']
            ]]);

        // ================

        // Fetch
        $response = $this->json('GET', "/api/foodfleet/$endpoint?filter[name]=c");

        // Assert
        $response
            ->assertOk()
            ->assertExactJson(['data' => []]);
    }
}
