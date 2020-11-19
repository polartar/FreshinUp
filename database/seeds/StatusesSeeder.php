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
        UserStatus::truncate();
           
        $statuses = [
            1 => 'Prospect / Lead',
            2 => 'Pending Invitation',
            3 => 'Pending Review',
            4 => 'Approved',
            5 => 'On Hold',
        ];

        foreach($statuses as $id => $name) {
            UserStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
