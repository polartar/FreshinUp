<?php


namespace App\Models\Foodfleet;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

/**
 * Class Venue
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property int status_id
 * @property string owner_uuid
 * @property string $address
 * @property string $address_line_1
 * @property string $address_line_2
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 *
 * @property Location[] locations
 * @property Document[] documents
 * @property Event[] events
 * @property User owner
 * @property VenueStatus status
 */
class Venue extends Model
{
    use SoftDeletes;
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['address'];
    protected $casts = [
        'status_id' => 'int'
    ];

    public function getAddressAttribute()
    {
        return $this->address_line_1 . ' ' . $this->address_line_2;
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'venue_uuid', 'uuid');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'venue_uuid', 'uuid');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(VenueStatus::class, 'status_id', 'id');
    }
}
