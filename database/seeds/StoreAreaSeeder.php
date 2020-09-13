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

        $store = Store::get();

        for ($i = 0; $i < 10; $i++) {
            factory(StoreArea::class)->create([
                'store_uuid' => $store->random()->uuid,
            ]);
        }
    }
}
