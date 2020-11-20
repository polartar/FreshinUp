<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Document\Template\Template;
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
 * @property string template_uuid
 * @property string event_store_uuid
 *
 *
 * @property \App\User owner
 * @property DocumentType type
 * @property DocumentStatus status
 * @property Template template
 * @property \App\User|Store|Event|Location assigned
 */
class Document extends Model implements HasMedia
{
    use SoftDeletes;
    use GeneratesUuid;
    use HasMediaTrait;
    const FILLABLES = [
        'title',
        'status_id',
        'type_id',
        'description',
        'notes',
        'expiration_at',
        'created_by_uuid',
        'created_at',
        'updated_at',
        'deleted_at',
        'assigned_uuid',
        'assigned_type',
        'template_uuid',
        'event_store_uuid',
        'file'
    ];
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

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_uuid', 'uuid');
    }
}
