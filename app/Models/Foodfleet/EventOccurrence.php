<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

/**
 * Class EventOccurrence
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string uuid
 * @property string event_schedule_uuid
 * @property \DateTime start
 * @property \DateTime end_at
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 * @property EventSchedule schedule
 */
class EventOccurrence extends Model
{
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];

    public function schedule()
    {
        // TODO: this should be renamed to eventSchedule to be more concise
        return $this->belongsTo(EventSchedule::class, 'event_schedule_uuid', 'uuid');
    }
}
