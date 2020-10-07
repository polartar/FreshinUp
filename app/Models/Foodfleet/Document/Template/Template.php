<?php

namespace App\Models\Foodfleet\Document\Template;

use App\Models\Model;
use Dyrynda\Database\Support\GeneratesUuid;

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
}
