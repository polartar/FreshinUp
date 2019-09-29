<?php

namespace App\Models\Foodfleet;

use Dyrynda\Database\Support\GeneratesUuid;

class Address extends \FreshinUp\FreshBusForms\Models\Address\Address
{
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];

    public function getMorphClass()
    {
        return 'FreshinUp\FreshBusForms\Models\Address\Address';
    }
}
