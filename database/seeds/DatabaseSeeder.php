<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \FinancialModifiersTableSeeder::class,
            \StatusesSeeder::class,
            \UserLevelTypeSeeder::class,
            \CompanyTypeSeeder::class,
            \StoreStatusesSeeder::class,
            \StoreTypesSeeder::class,
            \EventStatusesSeeder::class
        ]);
    }
}
