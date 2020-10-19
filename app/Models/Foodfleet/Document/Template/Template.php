<?php

namespace App\Models\Foodfleet\Document\Template;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use FreshinUp\FreshBusForms\Models\User\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class Template
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string uuid
 * @property string title
 * @property string description
 * @property string content
 * @property int status_id
 * @property \App\User updatedBy
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 *
 *
 * @property Status status
 */
class Template extends Model
{
    protected $table = 'document_templates';
    protected $guarded = ['id', 'uuid'];
    use GeneratesUuid;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_uuid', 'uuid');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($template) {
            /** @var \App\User $user */
            $user = Auth::user();
            if ($user) {
                $template->updated_by_uuid = $user->uuid;
            }
        });
        static::updating(function ($template) {
        });
    }
}