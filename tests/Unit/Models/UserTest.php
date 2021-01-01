<?php

namespace Tests\Unit\Models;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * @group userTest
     */
    public function testModel()
    {
        /** @var User $user */
        $manager = factory(User::class)->create();
        $user = factory(User::class)->create([
            'manager_uuid' => $manager->uuid
        ]);
        $this->assertDatabaseHas('users', [
            'manager_uuid' => $user->manager_uuid,
            // other fields are (should be) tested on fresh bus
        ]);

        $this->assertEquals($user->manager_uuid, $user->manager->uuid);
    }
}
