<?php
/**
 * Created by PhpStorm.
 * User: giuseppe
 * Date: 16/09/19
 * Time: 18.35
 */

namespace App\Models\Foodfleet;

class Company extends \FreshinUp\FreshBusForms\Models\Company\Company
{
    public function getMorphClass()
    {
        return 'FreshinUp\FreshBusForms\Models\Company\Company';
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
