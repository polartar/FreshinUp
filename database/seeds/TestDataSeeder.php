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
            AddressesTableSeeder::class,
            PaymentTypesTableSeeder::class,
            DevicesTableSeeder::class,
            SupplierCompaniesTableSeeder::class,
            HostCompaniesTableSeeder::class,
            CategoriesTableSeeder::class,
            StaffsTableSeeder::class,
            StoreTagsTableSeeder::class,
            StoresTableSeeder::class,
            EventTagsTableSeeder::class,
            StoreAreaSeeder::class,
            VenuesSeeder::class,
            VenueStatusesSeeder::class,
            LocationCategorySeeder::class,
            LocationsTableSeeder::class,
            EventsTableSeeder::class,
            CustomersTableSeeder::class,
            ItemsTableSeeder::class,
            TransactionsTableSeeder::class,
            PaymentsTableSeeder::class,
            FinancialReportsTableSeeder::class,
            DocumentsTableSeeder::class,
            VenueStatusesSeeder::class,
        ]);
    }
}
