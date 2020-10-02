<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentTemplateType
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 *
 *
 * @property DocumentTemplate[] templates
 */
class DocumentTemplateType extends Model
{
    public $timestamps = false;
    public function templates ()
    {
        return $this->hasMany(DocumentTemplate::class, 'type_id');
    }
}
