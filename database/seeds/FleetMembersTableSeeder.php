<?php

use Illuminate\Database\Seeder;

class FleetMembersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Foodfleet\FleetMember::class, 50)->create();
    }
}
