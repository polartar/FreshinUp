<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('venues', 'status_id');
    }
}
