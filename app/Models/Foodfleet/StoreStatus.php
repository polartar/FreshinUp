<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class StoreStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 *
 * @property Store[] stores
 */
class StoreStatus extends Model
{
    public function stores()
    {
        return $this->hasMany(Store::class, 'status_id');
    }
}
