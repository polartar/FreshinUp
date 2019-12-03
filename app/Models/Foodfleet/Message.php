<?php

namespace App\Models\Foodfleet;

use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_uuid', 'uuid');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_uuid', 'uuid');
    }
}
