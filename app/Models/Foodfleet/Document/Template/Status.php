<?php

namespace App\Models\Foodfleet\Document\Template;

use App\Models\Model;

/**
 * Class Status
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string name
 *
 *
 * @property Template[] templates
 */
class Status extends Model
{
    protected $table = 'document_template_statuses';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function templates()
    {
        return $this->hasMany(Template::class, 'status_id', 'id');
    }
}
