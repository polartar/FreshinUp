<?php

use Illuminate\Database\Seeder;
use App\Enums\EventType as EventTypeEnums;
use App\Models\Foodfleet\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = EventTypeEnums::toKeyedSelectArray();
        foreach($types as $id => $name) {
            EventType::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
