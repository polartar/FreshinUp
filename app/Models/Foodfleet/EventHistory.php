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
 */
class EventHistory extends Model
{
    public $timestamps = false;
    protected $dates = ['date'];
    protected $casts = [
        'status_id' => 'int'
    ];
    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class);
    }
}
