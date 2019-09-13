<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\FinancialSummary;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\FleetMember;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
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

        $customers = $this->createCustomers(2);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(2)->toDateTimeString(),
            'customer_uuid' => $customers->first()->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'MASTERCARD')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customers->last()->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customers->last()->uuid
        ]);

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
            'avg_ticket' => 1500
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
        $customer = factory(Customer::class)->create();
        $event = factory(Event::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'event_uuid' => $event->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
    public function testGetListFilterByFleetMemberUuid()
    {
        $this->createPaymentTypes();
        $customer = factory(Customer::class)->create();
        $fleetMember = factory(FleetMember::class)->create();
        $event = factory(Event::class)->create([
            'fleet_member_uuid' => $fleetMember->uuid
        ]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'event_uuid' => $event->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[fleet_member_uuid]=" . $fleetMember->uuid)
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
    public function testGetListFilterByContractorUuid()
    {
        $this->createPaymentTypes();
        $customer = factory(Customer::class)->create();
        $contractor = factory(Company::class)->create();
        $fleetMember = factory(FleetMember::class)->create([
            'contractor_uuid' => $contractor->uuid
        ]);
        $event = factory(Event::class)->create([
            'fleet_member_uuid' => $fleetMember->uuid
        ]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'event_uuid' => $event->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
        ], $data);


        $data = $this
            ->json('get', "/api/foodfleet/financial-summary?filter[contractor_uuid]=" . $contractor->uuid)
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        $eventTag = factory(EventTag::class)->create();
        $event = factory(Event::class)->create();
        $event->eventTags()->sync([$eventTag->uuid]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'event_uuid' => $event->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        $location = factory(Location::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'location_uuid' => $location->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer2->uuid
        ]);
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
        $customer = factory(Customer::class)->create();
        $staff = factory(Staff::class)->create();
        $location = factory(Location::class)->create();
        $location->staffs()->sync([$staff->uuid]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'location_uuid' => $location->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        $device = factory(Device::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
            'device_uuid' => $device->uuid
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        $item = factory(Item::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $payment = factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        $payment->items()->sync(['item_uuid' => $item->uuid]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        $category = factory(Category::class)->create();
        $item = factory(Item::class)->create(['category_uuid' => $category->uuid]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $payment = factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        $payment->items()->sync(['item_uuid' => $item->uuid]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 2000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'net' => 2400,
            'cash' => 2000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 2000
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
            'avg_ticket' => 3000
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
            'net' => 1600,
            'cash' => 2000,
            'credit' => 0,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 2000
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 2000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'net' => 2400,
            'cash' => 2000,
            'credit' => 1000,
            'sales_type' => [
                [
                    'name' => 'CASH',
                    'value' => 2000
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
            'avg_ticket' => 3000
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
        $customer = factory(Customer::class)->create();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $payment = factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'CASH')->first()->uuid,
            'square_created_at' => Carbon::now()->subDays(1)->toDateTimeString(),
            'customer_uuid' => $customer->uuid,
        ]);
        factory(Payment::class)->create([
            'total_money' => 1000,
            'payment_type_uuid' => PaymentType::where('name', 'VISA')->first()->uuid,
            'square_created_at' => Carbon::now()->toDateTimeString(),
            'customer_uuid' => $customer->uuid
        ]);
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
            'avg_ticket' => 2000
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
