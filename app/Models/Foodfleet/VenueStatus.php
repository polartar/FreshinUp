<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VenueStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property Venue[] venues
 */
class VenueStatus extends Model
{
    public function venues()
    {
        return $this->hasMany('Venues', 'status_id');
    }
}
