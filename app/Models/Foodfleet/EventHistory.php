<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    public $timestamps = false;

    public function event()
    {
        return $this->hasOne(Event::class, 'event_uuid');
    }

    public function status()
    {
        return $this->hasOne(EventStatus::class, 'status_id');
    }
}
