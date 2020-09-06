<?php

namespace App\Models\Foodfleet;

use FreshinUp\FreshBusForms\Models\Company\Company as BusCompany;

class Company extends BusCompany
{
    public function getMorphClass()
    {
        return BusCompany::class;
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'supplier_uuid', 'uuid');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'host_uuid', 'uuid');
    }
}
