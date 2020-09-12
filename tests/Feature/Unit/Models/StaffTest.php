<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffTest extends TestCase
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

        $staff = factory(Staff::class)->create();
        $staff->stores()->sync($store->uuid);

        $this->assertDatabaseHas('staffs', [
            'uuid' => $staff->uuid
        ]);

        $this->assertDatabaseHas('stores_staffs', [
            'staff_uuid' => $staff->uuid,
            'store_uuid' => $store->uuid
        ]);
    }
}
