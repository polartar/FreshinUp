<?php

namespace App\Models\Foodfleet;

use App\Http\Controllers\Foodfleet\Events\Events;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_uuid', 'uuid');
    }

    public function status()
    {
        return $this->hasOne(EventStatus::class, 'status_id');
    }
}
