<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class StoreArea
 * @package App
 *
 * @property int id
 * @property string name
 * @property int radius
 * @property string state
 * @property string store_uuid
 *
 *
 * @property Store store
 */
class StoreArea extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = [
        'radius' => 'int'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }
}
