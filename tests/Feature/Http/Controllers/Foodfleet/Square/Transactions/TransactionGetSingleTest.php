<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Transactions;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Store;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionGetSingleTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetWithIncluded()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $category = factory(Category::class)->create();
        $items = factory(Item::class, 2)->create(['category_uuid' => $category->uuid]);
        $store = factory(Store::class)->create();
        $location = factory(Location::class)->create();
        $event = factory(Event::class)->create(['location_uuid' => $location->uuid]);

        $transaction = factory(Transaction::class)->create([
            'store_uuid' => $store->uuid,
            'event_uuid' => $event->uuid
        ]);
        $itemUuids = $items->pluck('uuid')->toArray();
        $transaction->items()->sync([
            $itemUuids[0] => [
                'quantity' => $this->faker->numberBetween(1, 10),
            ],
            $itemUuids[1] => [
                'quantity' => $this->faker->numberBetween(1, 10)
            ]
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/transactions/" . $transaction->uuid . "?include=items.category" .
                ",store,event.location")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertArraySubset([
            'uuid' => $transaction->uuid,
            'square_id' => (String)$transaction->square_id,
            'square_created_at' => Carbon::parse($transaction->square_created_at)->toDateTimeString(),
            'square_updated_at' => null,
            'total_money' => (String)$transaction->total_money,
            'total_tax_money' => (String)$transaction->total_tax_money,
            'total_discount_money' => (String)$transaction->total_discount_money,
            'total_service_charge_money' => (String)$transaction->total_service_charge_money,
            'event' => [
                'uuid' => $transaction->event->uuid,
                'name' => $transaction->event->name,
                'location' => [
                    'uuid' => $transaction->event->location->uuid,
                    'name' => $transaction->event->location->name
                ]
            ],
            'items' => [
                0 => [
                    'uuid' => $transaction->items()->get()->first()->uuid,
                    'name' => $transaction->items()->get()->first()->name,
                    'quantity' => $transaction->items()->get()->first()->pivot->quantity,
                    'total_money' => $transaction->items()->get()->first()->pivot->total_money,
                    'total_tax_money' => $transaction->items()->get()->first()->pivot->total_tax_money,
                    'total_discount_money' => $transaction->items()->get()->first()->pivot->total_discount_money,
                    'category' => [
                        'uuid' => $transaction->items()->get()->first()->category->uuid,
                        'name' => $transaction->items()->get()->first()->category->name
                    ]
                ],
                1 => [
                    'uuid' => $transaction->items()->get()->last()->uuid,
                    'name' => $transaction->items()->get()->last()->name,
                    'quantity' => $transaction->items()->get()->last()->pivot->quantity,
                    'total_money' => $transaction->items()->get()->last()->pivot->total_money,
                    'total_tax_money' => $transaction->items()->get()->last()->pivot->total_tax_money,
                    'total_discount_money' => $transaction->items()->get()->last()->pivot->total_discount_money,
                    'category' => [
                        'uuid' => $transaction->items()->get()->last()->category->uuid,
                        'name' => $transaction->items()->get()->last()->category->name
                    ]
                ]
            ],
            'store' => [
                'uuid' => $transaction->store->uuid,
                'name' => $transaction->store->name,
                'square_id' => $transaction->store->square_id
            ],
        ], $data);
    }
}
