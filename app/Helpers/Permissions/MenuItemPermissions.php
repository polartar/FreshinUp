<?php

namespace App\Helpers\Permissions;

use FreshinUp\FreshBusForms\Helpers\Permissions;

class MenuItemPermissions extends Permissions
{
    public function getProperties($filters)
    {
        return [
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
    }
}
