<?php

use Illuminate\Database\Seeder;

class EventTagsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Foodfleet\EventTag::class, 5)->create();
    }
}
