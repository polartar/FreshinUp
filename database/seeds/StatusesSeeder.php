<?php

use FreshinUp\FreshBusForms\Models\User\UserStatus;
use App\Enums\UserStatus as UserStatusEnum;
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
        $statuses = UserStatusEnum::toKeyedSelectArray();
        foreach($statuses as $id => $name) {
            UserStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
