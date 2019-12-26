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
            \EventStatusesSeeder::class,
            \AddressesTableSeeder::class,
            \PaymentTypesTableSeeder::class,
            \DevicesTableSeeder::class,
            \SupplierCompaniesTableSeeder::class,
            \HostCompaniesTableSeeder::class,
            \CategoriesTableSeeder::class,
            \StaffsTableSeeder::class,
            \StoreTagsTableSeeder::class,
            \StoreStatusesSeeder::class,
            \StoreTypesSeeder::class,
            \StoresTableSeeder::class,
            \EventTagsTableSeeder::class,
            \VenuesTableSeeder::class,
            \LocationsTableSeeder::class,
            \EventsTableSeeder::class,
            \CustomersTableSeeder::class,
            \ItemsTableSeeder::class,
            \TransactionsTableSeeder::class,
            \PaymentsTableSeeder::class,
            \FinancialReportsTableSeeder::class,
            \DocumentTypesSeeder::class,
            \DocumentStatusesSeeder::class,
            \DocumentsTableSeeder::class,
        ]);
    }
}
