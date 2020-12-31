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

    public function testWhenEmptyDatabase()
    {
        $this->assertEquals(0, UserLevel::count());
        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");
        $this->assertEquals(0, UserLevel::count());
    }

    public function testWhenUserLevelAlreadySeeded()
    {
        Artisan::call("db:seed --class=UserLevelTypeSeeder");
        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");
        $this->assertEquals(3, UserLevel::count());
        $this->assertEquals(1, UserLevel::where('name', 'Super Admin')->count());
        $this->assertEquals(1, UserLevel::where('name', 'Company Owner')->count());
        $this->assertEquals(1, UserLevel::where('name', 'Company Employee')->count());
    }

    public function testWhenSeededTwice()
    {
        Artisan::call("db:seed --class=UserLevelTypeSeeder");
        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");
        $this->assertEquals(3, UserLevel::count());
        Artisan::call("db:seed --class=UserLevelTypeEnforcerSeeder");
        $this->assertEquals(3, UserLevel::count());
        $this->assertEquals(1, UserLevel::where('name', 'Super Admin')->count());
        $this->assertEquals(1, UserLevel::where('name', 'Company Owner')->count());
        $this->assertEquals(1, UserLevel::where('name', 'Company Employee')->count());
    }


    public function testThatTheEnforcerSeederIsRunWhenAllSeedsAreRun()
    {
        Artisan::call("db:seed");

        $accepted_roles = [
            "Super Admin",
            "Company Owner",
            "Company Employee",
        ];

        $this->assertSame($accepted_roles, UserLevel::get()->pluck('name')->toArray());
    }
}
