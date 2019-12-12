<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes;
    use GeneratesUuid;
    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }
}
