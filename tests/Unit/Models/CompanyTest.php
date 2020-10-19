<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $store = factory(Store::class)->create();
        $event = factory(Event::class)->create();

        $company = factory(Company::class)->create();
        $foodFleetCompany = \App\Models\Foodfleet\Company::find($company->id);
        $foodFleetCompany->events()->save($event);
        $foodFleetCompany->stores()->save($store);
        $foodFleetCompany->save();

        $this->assertDatabaseHas('companies', [
            'uuid' => $foodFleetCompany->uuid
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'host_uuid' => $foodFleetCompany->uuid
        ]);

        $this->assertDatabaseHas('stores', [
            'uuid' => $store->uuid,
            'supplier_uuid' => $foodFleetCompany->uuid
        ]);
    }
}
