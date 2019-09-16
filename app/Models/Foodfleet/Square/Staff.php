<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\FleetMember;
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
class Staff extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];
    protected $table = 'staffs';

    public function fleetMembers()
    {
        return $this->belongsToMany(
            FleetMember::class,
            'fleet_members_staffs',
            'staff_uuid',
            'fleet_member_uuid',
            'uuid',
            'uuid'
        );
    }
}
