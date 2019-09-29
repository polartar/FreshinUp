<?php

use Illuminate\Database\Seeder;
use FreshinUp\FreshBusForms\Models\Address\Country;

class AddressesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $countries = Country::get();
        for ($i = 0; $i < 50; $i++) {
            factory(\App\Models\Foodfleet\Address::class)->create([
                'country_id' => $countries->random()->id
            ]);
        }
    }
}
