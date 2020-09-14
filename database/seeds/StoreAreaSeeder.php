<?php

use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use Illuminate\Database\Seeder;

class StoreAreaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $stores = Store::get();
        foreach ($stores as $store) {
            for ($i = 0; $i < mt_rand(3,7); $i++) {
                factory(StoreArea::class)->create([
                    'store_uuid' => $store->uuid
                ]);
            }
        }
    }
}
