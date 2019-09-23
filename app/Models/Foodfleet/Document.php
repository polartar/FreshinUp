<?php


namespace App\Models\Foodfleet;

use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\User\User;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Document
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 */
class Document extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_uuid', 'uuid');
    }

    public function assignedFleetMember()
    {
        return $this->belongsTo(FleetMember::class, 'assigned_fleet_member_uuid', 'uuid');
    }

    public function assignedEvent()
    {
        return $this->belongsTo(Event::class, 'assigned_event_uuid', 'uuid');
    }
}
