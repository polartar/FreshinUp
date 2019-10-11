<?php

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
            1 => 'Super Admin',
            2 => 'Manager',
            5 => 'Company Owner',
            8 => 'Company Employee',
        ];

        $types = [
            1 => 'Food Fleet Staff',
            2 => 'Consumer'
        ];

        $displayIds = [];
        foreach($levels as $displayId => $name) {
            $forPlatform = $displayId < 5 ? 1 : 0;
            $forCompany = $displayId > 4 ? 1 : 0;
            UserLevel::updateOrCreate(
                ['display_id' => $displayId],
                [
                    'name' => $name,
                    'enabled' => 1,
                    'forPlatform' => $forPlatform,
                    'forCompany' => $forCompany,
                ]
            );
            $displayIds[] = $displayId;
        }

        $userLevels = UserLevel::whereNotIn('display_id', $displayIds)->get();
        foreach ($userLevels as $userLevel) {
            $userLevel->enabled = 0;
            $userLevel->save();
        }

        $displayIds = [];
        foreach($types as $displayId => $name) {
            UserType::updateOrCreate(
                ['display_id' => $displayId],
                ['name' => $name]
            );
            $displayIds[] = $displayId;
        }

        $userLevels = UserType::whereNotIn('display_id', $displayIds)->get();
        foreach ($userLevels as $userLevel) {
            $userLevel->delete();
        }
    }
}
