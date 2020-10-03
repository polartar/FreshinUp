<?php

namespace App\Models\Foodfleet\Document\Template;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string uuid
 * @property string title
 * @property int type_id
 * @property int status_id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 *
 *
 * @property Status status
 * @property Type type
 */
class Template extends Model
{
    protected $table = 'document_templates';
    protected $fillable = ['title', 'type_id', 'status_id'];
    use GeneratesUuid;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
