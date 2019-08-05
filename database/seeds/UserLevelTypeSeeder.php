<?php

namespace FoodFleet\Seeds;

use FreshinUp\FreshBusForms\Models\User\UserLevel;
use FreshinUp\FreshBusForms\Models\User\UserType;
use Illuminate\Database\Seeder;

class UserLevelTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            1 => 'FF Admin',
            2 => 'FF Director',
            3 => 'FF Lead',
            4 => 'FF Employee',
            8 => 'Employee',
        ];

        $types = [
            1 => 'FF Staff - Admin',
            2 => 'FF Staff',
            3 => 'Customer',
            4 => 'Customer - Employee',
            5 => 'Supplier',
            6 => 'Supplier - Employee',
        ];

        foreach($levels as $displayId => $name) {
            $forPlatform = $displayId < 5 ? 1 : 0;
            $forCompany = $displayId > 3 ? 1 : 0;
            UserLevel::updateOrCreate(
                ['display_id' => $displayId],
                [
                    'name' => $name,
                    'enabled' => 1,
                    'forPlatform' => $forPlatform,
                    'forCompany' => $forCompany,
                ]
            );
        }

        foreach($types as $displayId => $name) {
            UserType::updateOrCreate(
                ['display_id' => $displayId],
                ['name' => $name]
            );
        }
    }
}
