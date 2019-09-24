<?php


namespace App\Models\Foodfleet;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use FreshinUp\FreshBusForms\Models\Company\Company;
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
class FleetMember extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function contractor()
    {
        return $this->belongsTo(Company::class, 'contractor_uuid', 'uuid');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'fleet_member_uuid', 'uuid');
    }

    public function documents()
    {
        return $this->morphMany(Document::class , 'assigned' , 'assigned_type' , 'assigned_uuid' , 'uuid');
    }
}
