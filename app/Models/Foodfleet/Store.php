<?php


namespace App\Models\Foodfleet;

use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use Carbon\Carbon;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use FreshinUp\FreshBusForms\Traits\HasAddresses;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Store | This is what's called fleet member
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property int square_id
 * @property int status_id
 * @property int type_id
 * @property string supplier_uuid
 * @property string address_uuid
 * @property string website
 * @property string contact_phone
 * @property string size
 * @property string image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 *
 * @property StoreStatus status
 * @property StoreType type
 * @property Company supplier
 * TODO: annotate the following properties:
 * events, staffs, menus, transactions, tags, documents, menuItems, messages, status
 */
class Store extends Model implements HasMedia
{
    use SoftDeletes;
    use GeneratesUuid;
    use HasAddresses;
    use HasMediaTrait;

    protected $guarded = ['id', 'uuid'];
    protected $dates = ['deleted_at'];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('image')
            ->useDisk('bus')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('image');

        return null !== $media
            ? $media->getTemporaryUrl(Carbon::now()->addMinutes(5))
            : 'https://via.placeholder.com/800x600.png';
    }

    public function supplier()
    {
        return $this->belongsTo(Company::class, 'supplier_uuid', 'uuid');
    }

    public function type () {
        return $this->belongsTo(StoreType::class);
    }

    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'events_stores',
            'store_uuid',
            'event_uuid',
            'uuid',
            'uuid'
        )->withPivot([
            'commission_rate',
            'commission_type'
        ]);
    }

    public function staffs()
    {
        return $this->belongsToMany(
            Staff::class,
            'stores_staffs',
            'store_uuid',
            'staff_uuid',
            'uuid',
            'uuid'
        );
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'store_uuid', 'uuid');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_uuid', 'uuid');
    }

    public function tags()
    {
        return $this->belongsToMany(
            StoreTag::class,
            'stores_store_tags',
            'store_uuid',
            'store_tag_uuid',
            'uuid',
            'uuid'
        );
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }

    public function menuItems()
    {
        return $this->hasMany(EventMenuItem::class, 'store_uuid', 'uuid');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'store_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo(StoreStatus::class, 'status_id', 'id');
    }
}
