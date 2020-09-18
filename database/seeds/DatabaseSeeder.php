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
            FinancialModifiersTableSeeder::class,
            StatusesSeeder::class,
            UserLevelTypeSeeder::class,
            CompanyTypeSeeder::class,
            EventStatusesSeeder::class,
            EventTypeSeeder::class,
            StoreStatusesSeeder::class,
            StoreTypesSeeder::class,
            DocumentTypesSeeder::class,
            DocumentStatusesSeeder::class,
            LocationCategorySeeder::class,
        ]);
    }
}
