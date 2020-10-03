<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class DocumentType
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 *
 * @property Document[] documents
 */
class DocumentType extends Model
{
    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id');
    }
}
