<?php

use Illuminate\Database\Seeder;

class StoreTagsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Foodfleet\StoreTag::class, 5)->create();
    }
}
