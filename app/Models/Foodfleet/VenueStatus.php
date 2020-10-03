<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class VenueStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 *
 *
 * @property Venue[] venues
 */
class VenueStatus extends Model
{
    public $timestamps = false;
    public function venues()
    {
        return $this->hasMany(Venue::class, 'status_id');
    }
}
