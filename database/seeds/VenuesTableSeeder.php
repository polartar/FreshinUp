<?php

use App\Models\Foodfleet\Venue;
use Illuminate\Database\Seeder;

class VenuesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.*
     * @return void
     */
    public function run()
    {
        factory(Venue::class, 10)->create();
    }
}
