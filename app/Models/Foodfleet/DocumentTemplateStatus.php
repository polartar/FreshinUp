<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentTemplateStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 *
 *
 * @property DocumentTemplate[] templates
 */
class DocumentTemplateStatus extends Model
{
    public $timestamps = false;
    public function templates ()
    {
        return $this->hasMany(DocumentTemplate::class, 'status_id', 'id');
    }
}
