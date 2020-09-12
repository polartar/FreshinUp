<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $item = factory(Item::class)->create();
        $event = factory(Event::class)->create();
        $payment = factory(Payment::class)->create();

        $transaction = factory(Transaction::class)->create();
        $transaction->event()->associate($event);
        $transaction->payments()->save($payment);
        $transaction->save();
        $transaction->items()->attach($item->uuid, [
            'quantity' => $this->faker->randomNumber(),
            "total_money" => $this->faker->randomNumber(),
            "total_tax_money" => $this->faker->randomNumber(),
            "total_discount_money" => $this->faker->randomNumber()
        ]);


        $this->assertDatabaseHas('transactions', [
            'uuid' => $transaction->uuid,
            'event_uuid' => $event->uuid
        ]);

        $this->assertDatabaseHas('transactions_items', [
            'transaction_uuid' => $transaction->uuid,
            'item_uuid' => $item->uuid
        ]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'transaction_uuid' => $transaction->uuid
        ]);
    }
}
