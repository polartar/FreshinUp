<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\Location;
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

    public function locations()
    {
        return $this->belongsToMany(
            Location::class,
            'locations_staffs',
            'staff_uuid',
            'location_uuid',
            'uuid',
            'uuid'
        );
    }
}
