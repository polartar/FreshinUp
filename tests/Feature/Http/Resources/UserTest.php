<?php


namespace Tests\Feature\Http\Resources;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testResource()
    {
        $manager = factory(User::class)->create();
        $user = factory(User::class)->create([
            'manager_uuid' => $manager->uuid
        ]);
        $resource = new UserResource($user);
        $expected = [
            "uuid" => $user->uuid,
            "manager_uuid" => $user->manager_uuid,
            // TODO add remaining fields
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
