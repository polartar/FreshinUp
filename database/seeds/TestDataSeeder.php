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
            \PaymentTypesTableSeeder::class,
            \DevicesTableSeeder::class,
            \SupplierCompaniesTableSeeder::class,
            \HostCompaniesTableSeeder::class,
            \CategoriesTableSeeder::class,
            \StaffsTableSeeder::class,
            \StoresTableSeeder::class,
            \EventTagsTableSeeder::class,
            \LocationsTableSeeder::class,
            \EventStatusesSeeder::class,
            \EventsTableSeeder::class,
            \CustomersTableSeeder::class,
            \ItemsTableSeeder::class,
            \TransactionsTableSeeder::class,
            \PaymentsTableSeeder::class,
            \FinancialReportsTableSeeder::class,
            \DocumentTypesSeeder::class,
            \DocumentStatusesSeeder::class,
            \DocumentsTableSeeder::class
        ]);
    }
}
