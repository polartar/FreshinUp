<?php

namespace App\Models\Foodfleet;

use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string uuid
 * @property string event_uuid
 * @property string store_uuid
 * @property string recipient_uuid
 * @property string content
 * @property int status
 * @property string created_by_uuid
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property \DateTime deleted_at
 *
 *
 * @property Event event
 * @property Store store
 * @property User recipient
 * @property User owner
 */
class Message extends Model
{
    use SoftDeletes;
    use GeneratesUuid;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];
    protected $with = array('owner');

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
        // TODO: we should rename this to owner_uuid or change the relation name to be createdBy()
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_uuid', 'uuid');
    }
}
