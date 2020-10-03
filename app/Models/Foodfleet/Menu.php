<?php

namespace App\Models\Foodfleet;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Menu
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string uuid
 * @property string store_uuid
 * @property string item
 * @property string category
 * @property string description
 * @property int street_price
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property \DateTime deleted_at
 *
 *
 * @property Store store
 * @property EventMenuItem[] items
 */
class Menu extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }

    // TODO: need test
    public function items()
    {
        return $this->hasMany(EventMenuItem::class);
    }
}
