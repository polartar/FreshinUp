<?php

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \FinancialReportsTableSeeder::class,
            \CategoriesTableSeeder::class,
            \CustomersTableSeeder::class,
            \DevicesTableSeeder::class,
            \EventsTableSeeder::class,
            \EventTagsTableSeeder::class,
            \FleetMembersTableSeeder::class,
            \ItemsTableSeeder::class,
            \LocationsTableSeeder::class,
            \PaymentsTableSeeder::class,
            \PaymentTypesTableSeeder::class,
            \StaffsTableSeeder::class,
            \TransactionsTableSeeder::class,
            \ContractorCompaniesTableSeeder::class
        ]);
    }
}
