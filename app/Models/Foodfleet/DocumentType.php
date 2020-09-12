<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentType
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 */
class DocumentType extends Model
{
    public function documents()
    {
        return $this->hasMany('Document', 'type');
    }
}
