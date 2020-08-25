<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
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
