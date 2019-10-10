<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Square\Transaction;
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

    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'events_stores',
            'event_uuid',
            'store_uuid',
            'uuid',
            'uuid'
        );
    }

    public function host()
    {
        return $this->belongsTo(Company::class, 'host_uuid', 'uuid');
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
        return $this->belongsTo(EventStatus::class, 'status');
    }
}
