<?php

use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\StoreTag;
use App\Models\Foodfleet\StoreStatus;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $staffs = Staff::get();
        $tags = StoreTag::get();
        $statuses = StoreStatus::get();

        $suppliers = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'supplier');
        })->get();
        for ($i = 0; $i < 50; $i++) {
            $store = factory(\App\Models\Foodfleet\Store::class)->create([
                'status' => $statuses->random()->id,
                'supplier_uuid' => $suppliers->random()->uuid
            ]);

            $staffRandomUuids = $staffs->random(2)->pluck('uuid')->toArray();
            $store->staffs()->sync($staffRandomUuids);

            $tagRandomUuids = $tags->random(2)->pluck('uuid')->toArray();
            $store->storeTags()->sync($tagRandomUuids);
        }
    }
}
