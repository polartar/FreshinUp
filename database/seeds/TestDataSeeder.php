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
            \PaymentTypesTableSeeder::class,
            \DevicesTableSeeder::class,
            \ContractorCompaniesTableSeeder::class,
            \EventTagsTableSeeder::class,
            \StaffsTableSeeder::class,
            \FleetMembersTableSeeder::class,
            \LocationsTableSeeder::class,
            \EventsTableSeeder::class,
            \TransactionsTableSeeder::class,
            \CustomersTableSeeder::class,
            \ItemsTableSeeder::class,
            \PaymentsTableSeeder::class,
        ]);
    }
}
