<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\Event;
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
class Payment extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_uuid', 'uuid');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_uuid', 'uuid');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_uuid', 'uuid');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_uuid', 'uuid');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'payments_items',
            'payment_uuid',
            'item_uuid',
            'uuid',
            'uuid'
        );
    }
}
