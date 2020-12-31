<?php

namespace App\Tests\Unit\Database\Seeds;

use FreshinUp\FreshBusForms\Models\User\UserLevel;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLevelTypeEnforcerSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group user_roles
     * @test
     */
    public function testUserLevelTypesAreLimitedToOnlyThree()
    {
        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");

        //ensure there are only 3 roles, SuperAdmin, Company Owner and Company Employee
        $this->assertCount(3, UserLevel::get());
    }

    /**
     * @group user_roles
     * @test
     */
    public function testUserLevelTypesAreDeletedOrAddedIfTheRequiredRolesAreNotAvailableInDB()
    {
        //Given

        //there are existing roles in DB which include or exclude the required roles
        $levels = [
            1 => 'Super Admin',
            2 => 'Manager',
            5 => 'Company Owner',
            8 => 'Company Employee',
            9 => 'Another Unwanted Role',
            10 => 'Same For This Guy',
        ];

        UserLevel::unguard(true);

        foreach($levels as $id => $name) {
            $forPlatform = $id < 5 ? 1 : 0;
            $forCompany = $id > 4 ? 1 : 0;

            UserLevel::updateOrCreate(
                ['display_id' => $id],
                [
                    'name' => $name,
                    'enabled' => 1,
                    'default' => 1,
                    'forPlatform' => $forPlatform,
                    'forCompany' => $forCompany,
                ]
            );
        }

        UserLevel::reguard();

        //ensure all those roles have been saved
        $this->assertCount(6, UserLevel::get());

        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");

        //ensure only 3 roles exist after this
        $this->assertCount(3, UserLevel::get());

        //also ensure these are the only roles in DB
        $accepted_roles = [
            "Super Admin",
            "Company Owner",
            "Company Employee",
        ];

        $this->assertSame($accepted_roles, UserLevel::get()->pluck('name')->toArray());
    }

    /**
     * @test
     * @group user_roles
     */
    public function testThatTheEnforcerSeederIsRunWhenAllSeedsAreRun()
    {
        Artisan::call("db:seed");//all seeders

        $accepted_roles = [
            "Super Admin",
            "Company Owner",
            "Company Employee",
        ];

        $this->assertSame($accepted_roles, UserLevel::get()->pluck('name')->toArray());
    }
}