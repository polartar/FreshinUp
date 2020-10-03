<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class StoreType
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreType extends Model
{
    public function stores()
    {
        return $this->hasMany(Store::class, 'type');
    }
}
