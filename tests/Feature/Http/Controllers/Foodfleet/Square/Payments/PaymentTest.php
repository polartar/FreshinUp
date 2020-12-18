<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Payments;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentStatus;
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
                'name' => $payment->name,
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

    public function testCreatedItemWhenEmpty()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = [];
        $data = $this->json('POST', 'api/foodfleet/payments', $payload)
            ->assertStatus(422)
            ->json('errors');

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('amount_money', $data);
        $this->assertArrayHasKey('due_date', $data);
        $this->assertArrayHasKey('store_uuid', $data);
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

    public function testGetListWithInclude()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $paymentStatus = factory(PaymentStatus::class)->create();
        $event = factory(Event::class)->create();

        $payment = factory(Payment::class)->create([
            'status_id' => $paymentStatus->id,
            'event_uuid' => $event->uuid
        ]);

        $response = $this->json('GET', '/api/foodfleet/payments?include=status,event');
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');

        $this->assertArraySubset([
            'uuid' => $payment->uuid,
            'name' => $payment->name,
        ], $data[0]);


        $this->assertArraySubset([
            'id' => $paymentStatus->id,
            'name' => $paymentStatus->name,
        ], $data[0]['status']);


        $this->assertArraySubset([
            'uuid' => $event->uuid,
            'name' => $event->name,
        ], $data[0]['event']);
    }

    public function testGetListWithEventIncluded()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payments = factory(Payment::class, 5)->create();
        $data = $this->json('get', "/api/foodfleet/payments?include=event")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($payments as $idx => $payment) {
            $event = $payment->event;
            $this->assertArraySubset([
                'uuid' => $payment->uuid,
                'name' => $payment->name,
                'event' => [
                    'uuid' => $event->uuid,
                    'name' => $event->name,
                    'location_uuid' => $event->location_uuid,
                    'venue_uuid' => $event->venue_uuid
                ]
            ], $data[$idx]);
        }
    }
    public function testUpdateNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Payment::class)->make()->toArray();

        $this->json('PUT', 'api/foodfleet/payments/abc', $payload)
            ->assertStatus(404);
    }

    public function testUpdateWithInvalidPayload()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payment = factory(Payment::class)->create();
        $payload = factory(Payment::class)->make()->toArray();
        $payload['status_id'] = 100;
        $payload['store_uuid'] = 'abc123';
        $payload['event_uuid'] = 'abc123';

        $errors = $this->json('PUT', '/api/foodfleet/payments/'.$payment->uuid, $payload)
            ->assertStatus(422)
            ->json('errors');
        $this->assertArrayHasKey('status_id', $errors);
        $this->assertArrayHasKey('store_uuid', $errors);
        $this->assertArrayHasKey('event_uuid', $errors);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payment = factory(Payment::class)->create();
        $payload = factory(Payment::class)->make()->toArray();

        $data = $this->json('PUT', '/api/foodfleet/payments/'.$payment->uuid, $payload)
            ->assertStatus(200)
            ->json('data');
        $expected = [
            'id' => $payment->id,
            'uuid' => $payment->uuid,
            'name' => $payload['name'],
            "square_id" => $payload['square_id'],
            'description' => $payload['description'],
            'status_id' => $payload['status_id'],
            'amount_money' => round($payload['amount_money'], 2),
            'tip_money' => round($payload['tip_money'], 2),
            'due_date' => $payload['due_date'],
            'square_created_at' => $payload['square_created_at'],
            'store_uuid' => $payload['store_uuid'],
            'transaction_uuid' => $payload['transaction_uuid'],
            'payment_type' => $payload['payment_type'],
            'device_uuid' => $payload['device_uuid'],
            'payment_type_uuid' => $payload['payment_type_uuid'],
            'processing_fee_money' => round($payload['processing_fee_money'], 2),
            'event_uuid' => $payload['event_uuid'],
        ];
        $this->assertArraySubset($expected, $data);
    }
}
