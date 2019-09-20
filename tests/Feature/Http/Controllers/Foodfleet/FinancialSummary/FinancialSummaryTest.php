<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\FinancialSummary;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use App\User;
use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinancialSummaryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetList()
    {
        $this->createPaymentTypes();
        $user = factory(User::class)->create();

        Passport::actingAs($user);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [],
            'gross' => 0,
            'net' => 0,
            'cash' => 0,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 0
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 0
        ], $data);

        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(2)->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'MASTERCARD')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-19'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 3000,
            'net' => 2400,
            'cash' => 1000,
            'credit' => 2000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 1000
                ]
            ],
            'avg_ticket' => 1000
        ], $data);
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[event_uuid]=" . $event->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        $event = factory(Event::class)->create();
        $event->stores()->sync($store->uuid);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[store_uuid]=" . $store->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[supplier_uuid]=" . $supplier->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[host_uuid]=" . $host->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[date_before]=2019-05-21")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[date_after]=2019-05-21")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 0,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 0
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[event_tag_uuid]=" . $eventTag->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[location_uuid]=" . $location->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        $customer2 = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer2->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[customer_uuid]=" . $customer->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'event_uuid' => $event->uuid
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[staff_uuid]=" . $staff->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'device_uuid' => $device->uuid
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[device_uuid]=" . $device->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $transaction = factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ]);
        $transaction->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        $transaction->items()->sync([$item->uuid => ['quantity' => 2]]);
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[item_uuid]=" . $item->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $transaction = factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ]);
        $transaction->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        $transaction->items()->sync([$item->uuid => ['quantity' => 2]]);
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[category_uuid]=" . $category->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 2000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 2000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 3000,
            'net' => 2600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1500
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[min_price]=2000")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 2000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 2000,
            'net' => 1800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 2000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 2000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString()
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 2000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 3000,
            'net' => 2600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1500
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[max_price]=1000")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 0,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 0
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[payment_type_uuid]=" .
                PaymentType::where('name', 'CASH')->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
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
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $payment = factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
        ]));
        factory(Transaction::class)->create([
            'total_money' => 1000,
            'total_tax_money' => 200,
            'square_created_at' => Carbon::now()->toDateTimeString(),
        ])->payments()->save(factory(Payment::class)->make([
            'amount_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
        ]));
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/foodfleet/financial-summary")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ],
                [
                    'value' => 1000,
                    'date' => '2019-05-21'
                ]
            ],
            'gross' => 2000,
            'net' => 1600,
            'cash' => 1000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 1000
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[payment_uuid]=" . $payment->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals([
            'sales_time' => [
                [
                    'value' => 1000,
                    'date' => '2019-05-20'
                ]
            ],
            'gross' => 1000,
            'net' => 800,
            'cash' => 1000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 1000
                ],
                [
                    'name' => 'VISA',
                    'value' => 0
                ],
                [
                    'name' => 'MASTERCARD',
                    'value' => 0
                ]
            ],
            'avg_ticket' => 1000
        ], $data);
    }


    private function createPaymentTypes()
    {
        factory(PaymentType::class)->create(['name' => 'CASH']);
        factory(PaymentType::class)->create(['name' => 'VISA']);
        factory(PaymentType::class)->create(['name' => 'MASTERCARD']);
    }

    private function createCustomers($number)
    {
        return factory(Customer::class, $number)->create();
    }
}
