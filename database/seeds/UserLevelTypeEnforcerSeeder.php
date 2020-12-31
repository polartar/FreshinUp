<?php

use FreshinUp\FreshBusForms\Models\User\UserLevel;
use Illuminate\Database\Seeder;

class UserLevelTypeEnforcerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $accepted_roles = [
            1 => "Super Admin",
            5 => "Company Owner",
            8 => "Company Employee",
        ];

        UserLevel::whereNotIn('name', array_values($accepted_roles))->delete();
    }
}
