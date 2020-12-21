<?php

namespace Tests\Feature\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Square\PaymentStatus;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $items = factory(PaymentStatus::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/payment/statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($items as $idx => $item) {
            $this->assertArraySubset([
                'id' => $item->id,
                'name' => $item->name,
            ], $data[$idx]);
        }
    }
}
