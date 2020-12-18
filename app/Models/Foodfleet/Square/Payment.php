<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payment
 *
 * @property int id
 * @property string uuid
 * @property float amount_money
 * @property string description
 * @property string name
 * @property Carbon due_date
 * @property int status_id
 *
 * // Old fields
 * @property float tip_money
 * @property float processing_fee_money
 * @property int square_id
 * @property Carbon square_created_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property string device_uuid
 * @property string transaction_uuid
 * @property string payment_type_uuid
 * @property string store_uuid
 * @property string event_uuid
 *
 *
 * @property Event event
 * @property Store store
 * @property Device device
 * @property Transaction transaction
 * @property PaymentType type
 * @property PaymentStatus status
 */
class Payment extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $table = 'payments';
    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    const FILLABLES = [
         'amount_money',
         'description',
         'name',
         'due_date',
         'status_id',
         'tip_money',
         'processing_fee_money',
         'square_id',
         'square_created_at',
         'created_at',
         'updated_at',
         'deleted_at',
         'device_uuid',
         'transaction_uuid',
         'payment_type_uuid',
         'store_uuid',
         'event_uuid',
    ];
    protected $fillable = self::FILLABLES;

    const RULES = [
        'name' => 'required|string',
        'description' => 'nullable|string',
        'amount_money' => 'required|numeric',
        'due_date' => 'nullable|date',
        'status_id' => 'integer|exists:payment_statuses,id',
        'store_uuid' => 'required|exists:stores,uuid',
        'event_uuid' => 'required|exists:events,uuid'
    ];

    const EDIT_RULES = [
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'amount_money' => 'nullable|numeric',
        'due_date' => 'nullable|date',
        'status_id' => 'integer|exists:payment_statuses,id',
        'store_uuid' => 'exists:stores,uuid',
        'event_uuid' => 'exists:events,uuid'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_uuid', 'uuid');
    }

    public function type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_uuid', 'uuid');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }
}
