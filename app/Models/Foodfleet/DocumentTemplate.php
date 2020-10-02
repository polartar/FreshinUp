<?php

namespace App\Models\Foodfleet;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentTemplate
 * @package App
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
 * @property DocumentTemplateStatus status
 * @property DocumentTemplateType type
 */
class DocumentTemplate extends Model
{
    use GeneratesUuid;

    public function status () {
        return $this->belongsTo(DocumentTemplateStatus::class, 'status_id', 'id');
    }

    public function type () {
        return $this->belongsTo(DocumentTemplateType::class, 'type_id', 'id');
    }
}
