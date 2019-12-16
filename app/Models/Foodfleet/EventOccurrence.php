<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

class EventOccurrence extends Model
{
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];

    public function schedule()
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_uuid', 'uuid');
    }
}
