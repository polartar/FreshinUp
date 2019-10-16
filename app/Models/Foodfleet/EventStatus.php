<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    public function events()
    {
        return $this->hasMany(Event::class, 'status_id');
    }
}
