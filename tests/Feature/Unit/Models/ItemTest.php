<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $category = factory(Category::class)->create();
        $transaction = factory(Transaction::class)->create();

        $item = factory(Item::class)->create();
        $item->category()->associate($category);
        $item->save();
        $item->transactions()->sync([$transaction->uuid => ['quantity' => 1]]);

        $this->assertDatabaseHas('items', [
            'uuid' => $item->uuid,
            'category_uuid' => $category->uuid,
        ]);

        $this->assertDatabaseHas('transactions_items', [
            'transaction_uuid' => $transaction->uuid,
            'item_uuid' => $item->uuid,
            'quantity' => 1
        ]);
    }
}
