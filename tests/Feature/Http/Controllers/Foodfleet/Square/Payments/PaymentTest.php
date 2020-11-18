<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Payments;

use App\Models\Foodfleet\Square\Payment;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $payments = factory(Payment::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/payments")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($payments as $idx => $payment) {
            $this->assertArraySubset([
                'uuid' => $payment->uuid,
                'square_id' => $payment->square_id
            ], $data[$idx]);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithSquareIdFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Payment::class)->create([
            'square_id' => '111222',
        ]);

        $paymentToFind = factory(Payment::class)->create([
            'square_id' => '333222'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/payments")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/payments?filter[square_id]=333")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $paymentToFind->uuid,
            'square_id' => $paymentToFind->square_id
        ], $data[0]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithUuidFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Payment::class)->create();

        $paymentToFind = factory(Payment::class)->create();

        $data = $this
            ->json('get', "/api/foodfleet/payments")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/payments?filter[uuid]=" . $paymentToFind->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $paymentToFind->uuid,
            'square_id' => $paymentToFind->square_id
        ], $data[0]);
    }

    public function testCreatedItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Payment::class)->make()->toArray();
        $data = $this->json('POST', 'api/foodfleet/payments', $payload)
            ->assertStatus(201)
            ->json('data');

        $this->assertArraySubset([
            'name' => $payload['name'],
            'amount_money' => $payload['amount_money'],
            'description' => $payload['description'],
            'due_date' => $payload['due_date'],
            'status_id' => $payload['status_id'],
        ], $data);
    }
}
