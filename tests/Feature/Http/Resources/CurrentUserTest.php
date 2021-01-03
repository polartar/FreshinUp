<?php


namespace Tests\Feature\Http\Resources;

use App\Http\Resources\CurrentUser as CurrentUserResource;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class CurrentUserTest extends TestCase
{
    public function testResource()
    {
        $manager = factory(User::class)->create();
        $user = factory(User::class)->create([
            'manager_uuid' => $manager->uuid
        ]);
        $resource = new CurrentUserResource($user);
        $expected = [
            "uuid" => $user->uuid,
            "manager_uuid" => $user->manager_uuid,
            // remaining fields are (should be) tests on base class
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
