<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSchedule extends Model
{
    use SoftDeletes;
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];
    protected $with = array('scheduleOccurrences');

    public function event()
    {
        return $this->hasOne(Event::class, 'event_uuid', 'uuid');
    }

    public function scheduleOccurrences()
    {
        return $this->hasMany(EventOccurrence::class, 'event_schedule_uuid', 'uuid');
    }
}
