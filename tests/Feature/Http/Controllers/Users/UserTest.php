<?php

namespace Tests\Feature\Http\Controllers\CSM\Users;

use App\Models\CSM\Branch;
use App\Models\CSM\Company;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetSingle()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json(
                'get',
                "/api/currentUser"
            )
            ->assertStatus(200)
            ->json();

        $this->assertEquals(4, count($data['data_visibility']));
        $this->assertArraySubset([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'data_visibility' => $user->data_visibility
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $user2 = factory(User::class)->create();

        $data = $this->getStub();

        $this
            ->json(
                'put',
                'api/users/' . $user2->id,
                $data
            )
            ->assertStatus(200)
            ->json('data');

        $data['data_visibility'] = json_encode($data['data_visibility']);
        $this->assertDatabaseHas('users', $data);
    }


    /**
     * Get stub user data
     *
     * @return mixed
     */
    protected function getStub()
    {
        $data = factory(User::class)->make()->toArray();
        unset($data['name']);
        unset($data['email_verified_at']);
        unset($data['updated_at']);
        unset($data['created_at']);
        return $data;
    }
}
