<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $transaction = factory(Transaction::class)->create();

        $customer = factory(Customer::class)->create();
        $customer->transactions()->save($transaction);

        $this->assertDatabaseHas('customers', [
            'uuid' => $customer->uuid
        ]);

        $this->assertDatabaseHas('transactions', [
            'uuid' => $transaction->uuid,
            'customer_uuid' => $customer->uuid
        ]);
    }
}
