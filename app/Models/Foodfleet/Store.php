<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
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
class Store extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function supplier()
    {
        return $this->belongsTo(Company::class, 'supplier_uuid', 'uuid');
    }

    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'events_stores',
            'store_uuid',
            'event_uuid',
            'uuid',
            'uuid'
        );
    }

    public function staffs()
    {
        return $this->belongsToMany(
            Staff::class,
            'stores_staffs',
            'store_uuid',
            'staff_uuid',
            'uuid',
            'uuid'
        );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_uuid', 'uuid');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }
}
