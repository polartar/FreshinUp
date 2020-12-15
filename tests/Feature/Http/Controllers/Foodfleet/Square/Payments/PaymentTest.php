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

        $data = $this->json('get', "/api/foodfleet/payments")
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
                'name' => $payment->name
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
}
