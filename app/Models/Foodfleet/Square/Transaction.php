<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 * @package App\Models\Foodfleet\Square
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 *
 * @property Customer customer
 * @property Event event
 * @property Item[] items
 * @property Payment[] payments
 * @property Store store
 */
class Transaction extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

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
            'transactions_items',
            'transaction_uuid',
            'item_uuid',
            'uuid',
            'uuid'
        )->withPivot([
            'quantity',
            'total_money',
            'total_tax_money',
            'total_discount_money'
        ]);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'transaction_uuid', 'uuid');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }
}
