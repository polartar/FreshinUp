<?php

namespace Tests\Feature\Http\Controllers\Foodfleet;

use App\Enums\UserType;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use App\Models\Foodfleet\Event;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    public function testGetEventList()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => UserType::SUPPLIER,
            'company_id' => $company->id
        ]);
        Passport::actingAs($supplier);

        $events = factory(Event::class, 5)->create([
            'host_uuid' => $company->uuid
        ]);
        $data = $this->json('GET', "/api/foodfleet/suppliers/" . $supplier->company->uuid . "/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($events as $idx => $event) {
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name
            ], $data[$idx]);
        }
    }

    public function testGetEventListWithIncludes()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => UserType::SUPPLIER,
            'company_id' => $company->id
        ]);
        Passport::actingAs($supplier);
        $events = factory(Event::class, 5)->create([
            'host_uuid' => $company->uuid
        ]);
        $include = "location,status,host,location.venue,manager,event_tags,type,venue";
        $url = "/api/foodfleet/suppliers/" . $supplier->company->uuid . "/events?include=" . $include;
        $data = $this->json('GET', $url)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($events as $idx => $event) {
            $location = $event->location;
            $status = $event->status;
            $host = $event->host;
            $manager = $event->manager;
            $venue = $event->venue;
            $type = $event->type;
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name,
                'location' => [
                    "uuid" => $location->uuid,
                    "name" => $location->name,
                    "venue_uuid" => $location->venue_uuid,
                    "venue" => [
                        "uuid" => $location->venue->uuid,
                        "name" => $location->venue->name,
                    ],
                    "spots" => $location->spots,
                    "capacity" => $location->capacity,
                    "details" => $location->details
                ],
                'status' => [
                    'id' => $status->id,
                    'name' => $status->name
                ],
                'host' => [
                    'uuid' => $host->uuid,
                    'name' => $host->name
                ],
                'manager' => [
                    'uuid' => $manager->uuid,
                    'name' => $manager->name
                ],
                'venue' => [
                    'uuid' => $venue->uuid,
                    'name' => $venue->name,
                    'status_id' => $venue->status_id,
                    'owner_uuid' => $venue->owner_uuid,
                    'address_line_1' => $venue->address_line_1,
                    'address_line_2' => $venue->address_line_2,
                    'latitude' => $venue->latitude,
                    'longitude' => $venue->longitude
                ],
                'type' => [
                    'id' => $type->id,
                    'name' => $type->name
                ]
            ], $data[$idx]);
        }
    }

    public function testGetDocumentList()
    {
        $supplier = factory(User::class)->create([
            'type' => UserType::SUPPLIER
        ]);
        Passport::actingAs($supplier);
        $documents = factory(Document::class, 5)->create([
            'assigned_uuid' => $supplier->uuid
        ]);
        $data = $this->json('GET', "/api/foodfleet/suppliers/" . $supplier->uuid . "/documents")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($documents as $idx => $document) {
            $this->assertArraySubset([
                'uuid' => $document->uuid,
                'title' => $document->title,
                'assigned_uuid' => $document->assigned_uuid
            ], $data[$idx]);
        }
    }

    public function testGetStoreList()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => UserType::SUPPLIER,
            'company_id' => $company->id
        ]);
        Passport::actingAs($supplier);
        $stores = factory(Store::class, 5)->create([
            'supplier_uuid' => $company->uuid
        ]);
        $data = $this->json('GET', "/api/foodfleet/suppliers/" . $supplier->company->uuid . "/stores")
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

    public function testGetStoreListWithIncludes()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => UserType::SUPPLIER,
            'company_id' => $company->id
        ]);
        Passport::actingAs($supplier);
        $stores = factory(Store::class, 5)->create([
            'supplier_uuid' => $company->uuid
        ]);
        $include = "tags,addresses,events,supplier,supplier.admin,status,owner,type";
        $url = "/api/foodfleet/suppliers/" . $supplier->company->uuid . "/stores?include=" . $include;
        $data = $this->json('GET', $url)
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
