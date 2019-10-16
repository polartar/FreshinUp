<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\EventStatus;
use App\Enums\EventStatus as EventStatusEmums;


class EventStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = EventStatusEmums::toKeyedSelectArray();

        foreach($statuses as $id => $name) {
            EventStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
