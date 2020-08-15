<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    public function events()
    {
        return $this->hasMany(Event::class, 'type_id');
    }
}
