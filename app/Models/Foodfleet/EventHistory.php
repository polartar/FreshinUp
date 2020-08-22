<?php

namespace App\Models\Foodfleet;

use App\Http\Controllers\Foodfleet\Events\Events;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_uuid');
    }

    public function status()
    {
        return $this->hasOne(EventStatus::class, 'status_id');
    }
}
