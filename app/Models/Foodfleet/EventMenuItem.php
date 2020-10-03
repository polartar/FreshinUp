<?php

namespace App\Models\Foodfleet;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventMenuItem
 * @package App\Models\Foodfleet
 *
 * TODO: we normally wouldn't need his class since there is already MenuItem
 * By making MenuItem have a polymorphic relationship with Event
 * But there is also menu in the equation so I don't know. We will leave it as is for now
 *
 * @property int id
 * @property string uuid
 * @property string event_uuid
 * @property string store_uuid
 * @property string menu_uuid
 * @property string item
 * @property int servings
 * @property int cost
 * @property string description
 * @property int flag
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property \DateTime deleted_at
 *
 *
 * @property Event event
 * @property Store store
 * @property Menu menu
 */
class EventMenuItem extends Model
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

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_uuid', 'uuid');
    }
}
