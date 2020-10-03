<?php

namespace App\Models\Foodfleet\Document\Template;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string name
 *
 *
 * @property Template[] templates
 */
class Type extends Model
{
    protected $table = 'document_template_types';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function templates()
    {
        return $this->hasMany(Template::class, 'type_id');
    }
}
