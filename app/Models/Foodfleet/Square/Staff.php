<?php


namespace App\Models\Foodfleet\Square;

use App\Models\Foodfleet\Store;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Staff
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

    public function stores()
    {
        return $this->belongsToMany(
            Store::class,
            'stores_staffs',
            'staff_uuid',
            'store_uuid',
            'uuid',
            'uuid'
        );
    }
}
