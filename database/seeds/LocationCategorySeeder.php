<?php

use App\Enums\LocationCategory as LocationCategoryEnums;
use App\Models\Foodfleet\LocationCategory;
use Illuminate\Database\Seeder;

class LocationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = LocationCategoryEnums::toKeyedSelectArray();
        foreach ($types as $id => $name) {
            LocationCategory::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
