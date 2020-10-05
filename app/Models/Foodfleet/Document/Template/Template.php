<?php

namespace App\Models\Foodfleet\Document\Template;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use FreshinUp\FreshBusForms\Models\User\User;

/**
 * Class Template
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string uuid
 * @property string title
 * @property int status_id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 *
 *
 * @property Status status
 */
class Template extends Model
{
    protected $table = 'document_templates';
    protected $fillable = ['title', 'status_id'];
    use GeneratesUuid;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_uuid', 'uuid');
    }
}
