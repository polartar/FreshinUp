<?php


namespace App\Models\Foodfleet\Square;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payment
 *
 * @property int id
 * @property string uuid
 * @property int amount_money
 * @property string description
 * @property string name
 * @property Carbon due_date
 * @property int status_id
 *
 * // Old fields
 * @property int tip_money
 * @property int processing_fee_money
 * @property int square_id
 * @property Carbon square_created_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property string device_uuid
 * @property string transaction_uuid
 * @property string payment_type_uuid
 *
 *
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
}
