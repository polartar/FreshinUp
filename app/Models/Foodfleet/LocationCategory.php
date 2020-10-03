<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class LocationCategory
 * @package App\Models
 *
 * @property int id
 * @property int name
 *
 *
 * @property Location[] locations
 */
class LocationCategory extends Model
{
    public $timestamps = false;
    public function locations()
    {
        return $this->hasMany(Location::class, 'category_id', 'id');
    }
}
