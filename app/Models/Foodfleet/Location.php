<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\Staff;
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
class Location extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function events()
    {
        return $this->hasMany(Event::class, 'location_uuid', 'uuid');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'location_uuid', 'uuid');
    }

    public function staffs()
    {
        return $this->belongsToMany(
            Staff::class,
            'locations_staffs',
            'location_uuid',
            'staff_uuid',
            'uuid',
            'uuid'
        );
    }
}