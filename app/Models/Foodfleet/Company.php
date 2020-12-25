<?php

namespace App\Models\Foodfleet;

use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company as BusCompany;

/**
 * Class Company
 * @package App\Models\Foodfleet
 *
 *
 * @property string uuid
 * @property int id
 * @property int users_id
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
 */
class Company extends BusCompany
{
    protected $appends = ['is_supplier', 'is_customer'];

    public function getMorphClass()
    {
        return BusCompany::class;
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'supplier_uuid', 'uuid');
    }

    public function getIsSupplierAttribute ()
    {
        return array_reduce($this->attributes['company_types'], function($result, $type){
            return $result || ($type->key_id ==='supplier');
        }, false);
        
    }
    public function getIsCustomerAttribute ()
    {
        return array_reduce($this->attributes['company_types'], function($result, $type){
            return $result || ($type->key_id ==='host');
        }, false);
        
    }
    public function events()
    {
        return $this->hasMany(Event::class, 'host_uuid', 'uuid');
    }
}
