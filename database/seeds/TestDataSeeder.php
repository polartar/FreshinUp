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
            \SupplierCompaniesTableSeeder::class,
            \HostCompaniesTableSeeder::class,
            \StaffsTableSeeder::class,
            \StoresTableSeeder::class,
            \EventTagsTableSeeder::class,
            \LocationsTableSeeder::class,
            \EventsTableSeeder::class,
            \CustomersTableSeeder::class,
            \ItemsTableSeeder::class,
            \TransactionsTableSeeder::class,
            \PaymentsTableSeeder::class,
        ]);
    }
}
