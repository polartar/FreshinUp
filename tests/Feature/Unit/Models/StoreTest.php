<?php

namespace Tests\Feature\Unit\Models\Store;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $company = factory(Company::class)->create();
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $event = factory(Event::class)->create();
        $staff = factory(Staff::class)->create();

        $store = factory(Store::class)->create();
        $this->assertEquals($store->owner_uuid, $store->owner->uuid);
        $store->events()->save($event);
        $store->supplier()->associate($supplier);
        $store->save();
        $store->staffs()->sync($staff->uuid);

        $this->assertDatabaseHas('stores', [
            'uuid' => $store->uuid,
            'status_id' => $store->status_id,
            'address_uuid' => $store->address_uuid,
            'supplier_uuid' => $store->supplier_uuid,
            'owner_uuid' => $store->owner_uuid,
            'type_id' => $store->type_id,
            'square_id' => $store->square_id,
            'contact_phone' => $store->contact_phone,
            'size' => $store->size,
            'name' => $store->name,
            'pos_system' => $store->pos_system,
            'state_of_incorporation' => $store->state_of_incorporation,
            'website' => $store->website,
            'twitter' => $store->twitter,
            'facebook' => $store->facebook,
            'instagram' => $store->instagram,
            'staff_notes' => $store->staff_notes,
        ]);

        $this->assertDatabaseHas('events_stores', [
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $this->assertDatabaseHas('stores_staffs', [
            'staff_uuid' => $staff->uuid,
            'store_uuid' => $store->uuid
        ]);

        $this->assertDatabaseHas('users', [
            'uuid' => $store->owner_uuid,
        ]);

        $this->assertDatabaseHas('companies', [
            'uuid' => $store->supplier_uuid,
        ]);
    }
}
