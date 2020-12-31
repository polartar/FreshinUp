<?php

use FreshinUp\FreshBusForms\Models\User\UserLevel;
use FreshinUp\FreshBusForms\Models\User\UserType;
use App\Enums\UserType as UserTypeEnum;
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

        //delete all those not in the list above,

        UserLevel::whereNotIn('name', array_values($accepted_roles))->delete();
        
        foreach ($accepted_roles as $id => $role) {
            $level = UserLevel::whereName($role)->first();

            if (!$level) {
                $forPlatform = $id < 5 ? 1 : 0;
                $forCompany = $id > 4 ? 1 : 0;

                UserLevel::updateOrCreate(
                    ['display_id' => $id],
                    [
                        'name' => $role,
                        'enabled' => 1,
                        'default' => 1,
                        'forPlatform' => $forPlatform,
                        'forCompany' => $forCompany,
                    ]
                );
            }
        }
    }
}