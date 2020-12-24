<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Suppliers\Events;

use FreshinUp\FreshBusForms\Models\Company\Company;
use App\Models\Foodfleet\Event;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EventTest extends TestCase
{

    public function testGetList()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($supplier);

        $events = factory(Event::class, 5)->create(
            [
                'host_uuid' => $company->uuid
            ]
        );
        $url = "/api/foodfleet/supplier/" . $supplier->company->uuid . "/events";
        $response = $this->json('GET', $url);
        $data = $response
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

    public function testGetListWithIncludeds()
    {
        $company = factory(Company::class)->create();
        $supplier = factory(User::class)->create([
            'type' => 1,
            'company_id' => $company->id
        ]);

        Passport::actingAs($supplier);

        $events = factory(Event::class, 5)->create(
            [
                'host_uuid' => $company->uuid
            ]
        );
        $url = "/api/foodfleet/supplier/" . $supplier->company->uuid . "/events?include=location,status,host,location.venue,manager,event_tags,type,venue";
        $response = $this->json('get', $url);
        $data = $response
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
            $event_tags = $event->event_tags;
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
}
