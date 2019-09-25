<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Transactions;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Store;
use App\User;
use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
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
    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $transactions = factory(Transaction::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($transactions as $idx => $transaction) {
            $this->assertArraySubset([
                'uuid' => $transaction->uuid,
                'square_id' => $transaction->square_id
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

        factory(Transaction::class)->create([
            'square_id' => '111222',
        ]);

        $transactionToFind = factory(Transaction::class)->create([
            'square_id' => '333222'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[square_id]=333")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $transactionToFind->uuid,
            'square_id' => $transactionToFind->square_id
        ], $data[0]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithIncluded()
    {
        $this->createPaymentTypes();
        $user = factory(User::class)->create();

        Passport::actingAs($user);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?include=items,event.stores.supplier,event.host," .
            "event.event_tags,event.location,customer")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertEmpty($data);


        $location = factory(Location::class)->create();
        $customers = factory(Customer::class, 2)->create();
        $supplier = factory(Company::class)->create();
        $stores = factory(Store::class, 2)->create(['supplier_uuid' => $supplier->uuid]);
        $items = factory(Item::class, 2)->create();
        $host = factory(Company::class)->create();
        $eventTags = factory(EventTag::class, 2)->create();
        $events = factory(Event::class, 2)->create(['host_uuid' => $host->uuid, 'location_uuid' => $location->uuid]);
        foreach ($events as $event) {
            $event->eventTags()->sync($eventTags->pluck('uuid')->toArray());
            $event->stores()->sync($stores->pluck('uuid')->toArray());
        }

        $transactions = [];
        for ($i = 0; $i < 2; $i++) {
            $transaction = factory(Transaction::class)->create([
                'customer_uuid' => $customers->random()->uuid,
                'event_uuid' => $events->random()->uuid,
                'square_created_at' => $this->faker->dateTimeBetween('-190 days', 'now'),
                'square_updated_at' => $this->faker->dateTimeBetween('-190 days', 'now'),
                'store_uuid' => $stores->random()->uuid
            ]);
            $itemRandomUuids = $items->random(2)->pluck('uuid')->toArray();
            $transaction->items()->sync([
                $itemRandomUuids[0] => [
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
                $itemRandomUuids[1] => [
                    'quantity' => $this->faker->numberBetween(1, 10)
                ]
            ]);
            $transactions[] = $transaction;
        }

        $data = $this
            ->json('get', "/api/foodfleet/transactions?include=items,event.stores.supplier,event.host," .
            "event.event_tags,event.location,customer")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        foreach ($transactions as $idx => $transaction) {
            $this->assertArraySubset([
                'uuid' => $transaction->uuid,
                'square_id' => (String) $transaction->square_id,
                'square_created_at' => Carbon::parse($transaction->square_created_at)->toDateTimeString(),
                'square_updated_at' => Carbon::parse($transaction->square_updated_at)->toDateTimeString(),
                'total_money' => (String) $transaction->total_money,
                'total_tax_money' => (String) $transaction->total_tax_money,
                'total_discount_money' => (String) $transaction->total_discount_money,
                'total_service_charge_money' => (String) $transaction->total_service_charge_money,
                'customer' => [
                    'uuid' => $transaction->customer->uuid,
                    'name' => $transaction->customer->given_name . ' ' . $transaction->customer->family_name,
                    'square_id' => (String) $transaction->customer->square_id,
                    'reference_id' => $transaction->customer->reference_id
                ],
                'event' => [
                    'uuid' => $transaction->event->uuid,
                    'name' => $transaction->event->name,
                    'location' => [
                        'uuid' => $transaction->event->location->uuid,
                        'name' => $transaction->event->location->name
                    ],
                    'event_tags' => [
                        0 => [
                            'uuid' => $transaction->event->eventTags()->get()->first()->uuid,
                            'name' => $transaction->event->eventTags()->get()->first()->name,
                        ],
                        1 => [
                            'uuid' => $transaction->event->eventTags()->get()->last()->uuid,
                            'name' => $transaction->event->eventTags()->get()->last()->name,
                        ]
                    ],
                    'host' => [
                        'uuid' => $transaction->event->host->uuid,
                        'name' => $transaction->event->host->name,
                    ],
                    'stores' => [
                        0 => [
                            'uuid' => $transaction->event->stores()->get()->first()->uuid,
                            'name' => $transaction->event->stores()->get()->first()->name,
                            'square_id' => $transaction->event->stores()->first()->square_id,
                            'supplier' => [
                                'uuid' => $transaction->event->stores()->get()->first()->supplier->uuid,
                                'name' => $transaction->event->stores()->get()->first()->supplier->name,
                            ],
                        ],
                        1 => [
                            'uuid' => $transaction->event->stores()->get()->last()->uuid,
                            'name' => $transaction->event->stores()->get()->last()->name,
                            'square_id' => $transaction->event->stores()->get()->last()->square_id,
                            'supplier' => [
                                'uuid' => $transaction->event->stores()->get()->last()->supplier->uuid,
                                'name' => $transaction->event->stores()->get()->last()->supplier->name,
                            ],
                        ]
                    ]
                ],
                'items' => [
                    0 => [
                        'uuid' => $transaction->items()->get()->first()->uuid,
                        'name' => $transaction->items()->get()->first()->name,
                        'quantity' => $transaction->items()->get()->first()->pivot->quantity,
                    ],
                    1 => [
                        'uuid' => $transaction->items()->get()->last()->uuid,
                        'name' => $transaction->items()->get()->last()->name,
                        'quantity' => $transaction->items()->get()->last()->pivot->quantity,
                    ]
                ]
            ], $data[$idx], true);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByEventUuid()
    {
        $this->createPaymentTypes();
        $event = factory(Event::class)->create();

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);

        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[event_uuid]=" . $event->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByStoreUuid()
    {
        $this->createPaymentTypes();
        $store = factory(Store::class)->create();

        $transaction = factory(Transaction::class)->create([
            'store_uuid' => $store->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[store_uuid]=" . $store->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterBySupplierUuid()
    {
        $this->createPaymentTypes();
        $supplier = factory(Company::class)->create();
        $store = factory(Store::class)->create([
            'supplier_uuid' => $supplier->uuid
        ]);
        $event = factory(Event::class)->create();
        $event->stores()->sync($store->uuid);

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[supplier_uuid]=" . $supplier->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByHostUuid()
    {
        $this->createPaymentTypes();
        $host = factory(Company::class)->create();
        $event = factory(Event::class)->create(['host_uuid' => $host->uuid]);

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[host_uuid]=" . $host->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByDateBefore()
    {
        $this->createPaymentTypes();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $transaction = factory(Transaction::class)->create([
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ]);
        factory(Transaction::class)->create([
            'square_created_at' => Carbon::now()->toDateTimeString(),
        ]);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[date_before]=2019-05-21")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByDateAfter()
    {
        $this->createPaymentTypes();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $transaction  = factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->addDays(1)->toDateTimeString(),
        ]);
        factory(Transaction::class)->create([
            'square_created_at' => Carbon::now()->subDay()->toDateTimeString()
        ]);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[date_after]=2019-05-21")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByEventTagUuid()
    {
        $this->createPaymentTypes();
        $eventTag = factory(EventTag::class)->create();
        $event = factory(Event::class)->create();
        $event->eventTags()->sync([$eventTag->uuid]);

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[event_tag_uuid]=" . $eventTag->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByLocationUuid()
    {
        $this->createPaymentTypes();
        $location = factory(Location::class)->create();
        $event = factory(Event::class)->create(['location_uuid' => $location->uuid]);

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[location_uuid]=" . $location->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByCustomerUuid()
    {
        $this->createPaymentTypes();
        $customer = factory(Customer::class)->create();
        $transaction = factory(Transaction::class)->create([
            'customer_uuid' => $customer->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);

        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[customer_uuid]=" . $customer->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByStaffUuid()
    {
        $this->createPaymentTypes();
        $staff = factory(Staff::class)->create();
        $store = factory(Store::class)->create();
        $store->staffs()->sync([$staff->uuid]);
        $event = factory(Event::class)->create();
        $event->stores()->sync($store->uuid);

        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[staff_uuid]=" . $staff->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByDeviceUuid()
    {
        $this->createPaymentTypes();
        $device = factory(Device::class)->create();

        $transaction = factory(Transaction::class)->create();
        $transaction->payments()
            ->save(factory(Payment::class)->make([
                'device_uuid' => $device->uuid
            ]));
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[device_uuid]=" . $device->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByItemUuid()
    {
        $this->createPaymentTypes();
        $item = factory(Item::class)->create();

        $transaction = factory(Transaction::class)->create();
        $transaction->items()->sync([$item->uuid => ['quantity' => 2]]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[item_uuid]=" . $item->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByCategoryUuid()
    {
        $this->createPaymentTypes();
        $category = factory(Category::class)->create();
        $item = factory(Item::class)->create(['category_uuid' => $category->uuid]);

        $transaction = factory(Transaction::class)->create();
        $transaction->items()->sync([$item->uuid => ['quantity' => 2]]);
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[category_uuid]=" . $category->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByMinPrice()
    {
        $this->createPaymentTypes();

        $transaction = factory(Transaction::class)->create([
            'total_money' => 2000,
        ]);
        factory(Transaction::class)->create([
            'total_money' => 1000
        ]);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[min_price]=2000")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByMaxPrice()
    {
        $this->createPaymentTypes();

        $transaction = factory(Transaction::class)->create([
            'total_money' => 2000
        ]);
        factory(Transaction::class)->create([
            'total_money' => 3000
        ]);
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[max_price]=2000")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByPaymentTypeUuid()
    {
        $this->createPaymentTypes();

        $transaction = factory(Transaction::class)->create();
        $transaction->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[payment_type_uuid]=" .
                PaymentType::where('name', 'CASH')->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListFilterByPaymentUuid()
    {
        $this->createPaymentTypes();

        $transaction = factory(Transaction::class)->create();
        $transaction->payments()->save(factory(Payment::class)->make());
        $payment = $transaction->payments()->first();
        factory(Transaction::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/transactions")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);


        $data = $this
            ->json('get', "/api/foodfleet/transactions?filter[payment_uuid]=" . $payment->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertArraySubset([
            [
                'uuid' => $transaction->uuid
            ]
        ], $data);
    }


    private function createPaymentTypes()
    {
        factory(PaymentType::class)->create(['name' => 'CASH']);
        factory(PaymentType::class)->create(['name' => 'VISA']);
        factory(PaymentType::class)->create(['name' => 'MASTERCARD']);
    }
}
