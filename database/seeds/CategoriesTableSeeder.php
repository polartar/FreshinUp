<?php

use App\Models\Foodfleet\Company;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'supplier');
        })->get();
        for ($i = 0; $i < 50; $i++) {
            factory(\App\Models\Foodfleet\Square\Category::class)->create([
                'supplier_uuid' => $suppliers->random()->uuid
            ]);
        }
    }
}
