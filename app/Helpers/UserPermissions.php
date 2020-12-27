<?php

namespace App\Helpers;

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
            ]
            ]
        );
    }
}
