<?php


namespace App\Models\Foodfleet\Square;

use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
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
class Item extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function transactions()
    {
        return $this->belongsToMany(
            Transaction::class,
            'transactions_items',
            'item_uuid',
            'transaction_uuid',
            'uuid',
            'uuid'
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }
}
