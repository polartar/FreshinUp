<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = Store::get();
        
        for ($i = 0; $i < 5; $i++) {
            $item = factory(MenuItem::class)->create([
                'store_uuid' => $stores->random()->uuid
            ]);
        }
    }
}
