<?php

use FreshinUp\FreshBusForms\Models\User\UserLevel;
use FreshinUp\FreshBusForms\Models\User\UserStatus;
use FreshinUp\FreshBusForms\Models\User\UserType;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            1 => 'Active',
            2 => 'Idle',
            3 => 'On Hold',
            4 => 'Pending',
            5 => 'Prospect/Lead',
            6 => 'New',
        ];

        foreach($statuses as $id => $name) {
            UserStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
