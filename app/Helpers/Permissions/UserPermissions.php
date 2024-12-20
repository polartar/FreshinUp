<?php

namespace App\Helpers\Permissions;

use FreshinUp\FreshBusForms\Helpers\UserPermissions as BusUserPermissions;

class UserPermissions extends BusUserPermissions
{
    public function getProperties($filters)
    {
        return array_merge(
            parent::getProperties($filters),
            [
                'avatar' => [
                    'label' => 'Avatar',
                    'readonly' => false,
                    'rules' => []
                ],
                'manager_uuid' => [
                    'label' => 'Manager',
                    'readonly' => false,
                    'rules' => [
                        'exists:users,uuid'
                    ]
                ]
            ]
        );
    }
}
