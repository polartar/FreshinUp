<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreAreaTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $area = factory(StoreArea::class)->create();
        $this->assertDatabaseHas('store_areas', [
            'id' => $area->id,
            'name' => $area->name,
            'radius' => $area->radius,
            'state' => $area->state,
            'store_uuid' => $area->store_uuid,
        ]);
        $this->assertDatabaseHas('stores', [
            'uuid' => $area->store_uuid,
        ]);
        $this->assertEquals($area->store_uuid, $area->store->uuid);
    }
}
