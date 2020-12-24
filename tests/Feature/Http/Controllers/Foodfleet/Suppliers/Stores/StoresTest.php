<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Suppliers\Stores;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\User;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoresTest extends TestCase
{
    public function testGetList()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($supplier);

        $stores = factory(Store::class, 5)->create(
            [
                'supplier_uuid' => $company->uuid
            ]
        );
        $url = "/api/foodfleet/supplier/" . $supplier->company->uuid . "/stores";
        $response = $this->json('GET', $url);
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($stores as $idx => $store) {
            $this->assertArraySubset([
                'uuid' => $store->uuid,
                'name' => $store->name,
                'supplier_uuid' => $store->supplier_uuid
            ], $data[$idx]);
        }
    }

    public function testGetListWithIncludeds()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($supplier);

        $stores = factory(Store::class, 5)->create(
            [
                'supplier_uuid' => $company->uuid
            ]
        );
        $url = "/api/foodfleet/supplier/" . $supplier->company->uuid . "/stores?include=tags,addresses,events,supplier,supplier.admin,status,owner,type";
        $response = $this->json('GET', $url);
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($stores as $idx => $store) {
            $status = $store->status;
            $type = $store->type;
            $supplier = $store->supplier;
            $owner = $store->owner;
            $this->assertArraySubset([
                'uuid' => $store->uuid,
                'name' => $store->name,
                'supplier_uuid' => $store->supplier_uuid,
                'status' => [
                    'id' => $status->id,
                    'name' => $status->name
                ],
                'type' => [
                        'id' => $type->id,
                        'name' => $type->name
                    ],
                'supplier' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                        'uuid' => $supplier->uuid
                    ],
                'owner' => [
                        'id' => $owner->id,
                        'uuid' => $owner->uuid,
                        'first_name' => $owner->first_name,
                        'last_name' => $owner->last_name,
                        'email' => $owner->email,
                    ],
            ], $data[$idx]);
        }
    }
}
