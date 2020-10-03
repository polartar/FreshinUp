<?php


namespace App\Models\Foodfleet;

use App\User;
use App\Models\Foodfleet\Square\Transaction;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @property int id
 * @property string uuid
 * @property string name
 * @property int type_id
 * @property string location_uuid
 * @property \Datetime start_at
 * @property \Datetime end_at
 * @property string host_uuid
 * @property string host_status
 * @property string manager_uuid
 * @property int status_id
 * @property int budget
 * @property int attendees
 * @property int commission_rate
 * @property int commission_type
 * @property string venue_uuid
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string deleted_at
 *
 *
 * @property Store[] stores
 * @property Company[] host
 * @property User manager
 * @property Location[] location
 * @property Transaction[] transactions
 * @property EventTag[] eventTags
 * @property Document[] documents
 * @property EventStatus status
 * @property MenuItem[] menuItems
 * @property Message[] messages
 * @property EventSchedule schedule
 * @property EventType type
 * @property EventHistory[] histories
 * @property Venue venue
 */
class Event extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];
    protected $with = ['schedule'];

    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'events_stores',
            'event_uuid',
            'store_uuid',
            'uuid',
            'uuid'
        )->withPivot('commission_type', 'commission_rate');
    }

    public function host()
    {
        return $this->belongsTo(Company::class, 'host_uuid', 'uuid');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_uuid', 'uuid');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_uuid', 'uuid');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'event_uuid', 'uuid');
    }

    public function eventTags()
    {
        return $this->belongsToMany(
            EventTag::class,
            'events_event_tags',
            'event_uuid',
            'event_tag_uuid',
            'uuid',
            'uuid'
        );
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class, 'status_id', 'id');
    }

    public function menuItems()
    {
        return $this->hasMany(EventMenuItem::class, 'event_uuid', 'uuid');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'event_uuid', 'uuid');
    }

    public function schedule()
    {
        // TODO: rename this eventSchedule to be more concise and avoid confusion
        return $this->hasOne(EventSchedule::class, 'event_uuid', 'uuid');
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, 'type_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(EventHistory::class, 'event_uuid', 'uuid');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_uuid', 'uuid');
    }
}
