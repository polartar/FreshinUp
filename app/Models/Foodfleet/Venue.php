<?php


namespace App\Models\Foodfleet;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Venue
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 *
 * @property Location[] locations
 * @property Document[] documents
 * @property Event[] events
 */
class Venue extends Model
{
    use SoftDeletes;
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

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
}
