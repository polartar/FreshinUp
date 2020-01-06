<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\StoreType;
use App\Enums\StoreType as StoreTypeEmums;

class StoreTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = StoreTypeEmums::toKeyedSelectArray();

        foreach($types as $id => $name) {
            StoreType::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
