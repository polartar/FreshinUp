<?php

namespace Tests\Feature\Unit\Models\Store;

use App\Models\Foodfleet\Event;
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

        $store = factory(Store::class)->create();
        $store->events()->save($event);
        $store->supplier()->associate($supplier);
        $store->save();

        $this->assertDatabaseHas('stores', [
            'uuid' => $store->uuid,
            'supplier_uuid' => $supplier->uuid
        ]);

        $this->assertDatabaseHas('events_stores', [
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);
    }
}
