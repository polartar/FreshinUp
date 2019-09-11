<?php

use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::get();
        for ($i = 0; $i < 50; $i++) {
            factory(Item::class)->create([
                'category_uuid' => $categories->random()->uuid
            ]);
        }
    }
}
