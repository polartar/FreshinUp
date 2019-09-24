<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Square\Payment;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentType
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 */
class Event extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function fleetMember()
    {
        return $this->belongsTo(FleetMember::class, 'fleet_member_uuid', 'uuid');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_uuid', 'uuid');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'event_uuid', 'uuid');
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
        return $this->morphMany(Document::class , 'assigned' , 'assigned_type' , 'assigned_uuid' , 'uuid');
    }
}
