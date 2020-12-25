<?php

namespace App\Models\Foodfleet;

use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company as BusCompany;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;

/**
 * Class Company
 * @package App\Models\Foodfleet
 *
 *
 * @property string uuid
 * @property int id
 * @property int users_id - Manager
 * @property int status
 * @property string name
 * @property string address
 * @property string address2
 * @property string city
 * @property string state
 * @property string zip
 * @property string country
 * @property string website
 * @property string notes
 * @property string square_access_token
 * @property string square_refresh_token
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 *
 * @property Store[] stores
 * @property Event[] events
 * @property CompanyType type
 */
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
