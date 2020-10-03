<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class EventStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class EventStatus extends Model
{
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function history()
    {
        return $this->hasOne(EventHistory::class);
    }
}
