<?php


namespace App\Models\Foodfleet;

use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\User\User;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Document
 * @property int id
 * @property string uuid
 * @property string title
 * @property int status_id
 * @property int type_id
 * @property string description
 * @property string notes
 * @property Carbon expiration_at
 * @property string created_by_uuid
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string deleted_at
 * @property string assigned_uuid
 * @property string assigned_type
    // TODO: we might not need this field. remove and use assigned_uuid, assigned_type.
 * Leave it as is for now
 * @property string event_store_uuid
 *
 *
 * @property \App\User owner
 * @property DocumentType type
 * @property DocumentStatus status
 * @property \App\User|Store|Event|Location assigned
 */
class Document extends Model implements HasMedia
{
    use SoftDeletes;
    use GeneratesUuid;
    use HasMediaTrait;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];
    protected $with = array('owner');

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * When adding a file to attachment it will be stored on the cms disk.
     *
     * @return void
     */
    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('attachment')
            ->useDisk('cms')
            ->singleFile();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function assigned()
    {
        return $this->morphTo('assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(DocumentStatus::class);
    }

    public function type()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
