<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Permissions\MenuItemPermissions;
use App\User;

class MenuItemPermissionsTest extends \Tests\TestCase {
    public function testGetProperties () {
        $expected = [
            'store_uuid' => [
                'label' => 'Store uuid',
                'rules' => [
                    'required',
                    'string',
                    'exists:stores,uuid'
                ],
                'readonly' => false
            ],
            'title' => [
                'label' => 'Title',
                'rules' => [
                    'required',
                    'string',
                ],
                'readonly' => false
            ],
            'servings' => [
                'label' => 'Servings',
                'rules' => [
                    'required',
                    'integer',
                ],
                'readonly' => false
            ],
            'cost' => [
                'label' => 'Cost',
                'rules' => [
                    'required',
                    'integer',
                ],
                'readonly' => false
            ],
            'description' => [
                'label' => 'Description',
                'rules' => [
                    'string',
                ],
                'readonly' => false
            ],
        ];
        $user = factory(User::class)->create();
        $permissions = new MenuItemPermissions($user);
        $this->assertEquals($expected, $permissions->getProperties([]));
    }
}
