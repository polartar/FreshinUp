<?php

namespace App\Models\Foodfleet;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MenuItem
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string uuid
 * @property string store_uuid
 * @property string title
 * @property string description
 * @property int servings
 * @property int cost
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property \DateTime deleted_at
 *
 *
 * @property Store store
 */
class MenuItem extends Model
{
    use SoftDeletes;
    use GeneratesUuid;
    protected $dates = ['deleted_at'];

    const FILLABLES = [
        'store_uuid',
        'title',
        'description',
        'servings',
        'cost',
    ];
    protected $fillable = self::FILLABLES;

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }
}
