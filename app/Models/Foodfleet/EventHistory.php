<?php

namespace App\Models\Foodfleet;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventHistory
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string event_uuid
 * @property Event event
 * @property int status_id
 * @property EventStatus status
 * @property Carbon date
 * @property string description
 * @property bool completed
 */
class EventHistory extends Model
{
    public $timestamps = false;
    protected $dates = ['date'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class);
    }
}
