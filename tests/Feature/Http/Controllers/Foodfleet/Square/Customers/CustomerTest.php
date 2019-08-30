<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Customers;

use App\Models\Foodfleet\Square\Customer;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
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
    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $customers = factory(Customer::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/customers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($customers as $idx => $customer) {
            $this->assertArraySubset([
                'uuid' => $customer->uuid,
                'name' => $customer->given_name . ' ' . $customer->family_name,
                'first_name' => $customer->given_name,
                'last_name' => $customer->family_name,
                'square_id' => $customer->square_id,
                'reference_id' => $customer->reference_id
            ], $data[$idx]);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithTermFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Customer::class, 5)->create([
            'given_name' => 'Not visibles',
            'family_name' => 'Not visibles'
        ]);

        $customersToFind = factory(Customer::class, 5)->create([
            'given_name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/customers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/customers?term=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($customersToFind as $idx => $customer) {
            $this->assertArraySubset([
                'uuid' => $customer->uuid,
                'name' => $customer->given_name . ' ' . $customer->family_name,
                'first_name' => $customer->given_name,
                'last_name' => $customer->family_name,
                'square_id' => $customer->square_id,
                'reference_id' => $customer->reference_id
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

        factory(Customer::class)->create([
            'square_id' => '111222',
        ]);

        $customerToFind = factory(Customer::class)->create([
            'square_id' => '333222'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/customers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/customers?filter[square_id]=333")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $customerToFind->uuid,
            'name' => $customerToFind->given_name . ' ' . $customerToFind->family_name,
            'first_name' => $customerToFind->given_name,
            'last_name' => $customerToFind->family_name,
            'square_id' => $customerToFind->square_id,
            'reference_id' => $customerToFind->reference_id
        ], $data[0]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithReferenceIdFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Customer::class)->create([
            'reference_id' => '111222',
        ]);

        $customerToFind = factory(Customer::class)->create([
            'reference_id' => '333222'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/customers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/customers?filter[reference_id]=333")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $customerToFind->uuid,
            'name' => $customerToFind->given_name . ' ' . $customerToFind->family_name,
            'first_name' => $customerToFind->given_name,
            'last_name' => $customerToFind->family_name,
            'square_id' => $customerToFind->square_id,
            'reference_id' => $customerToFind->reference_id
        ], $data[0]);
    }
}
