<?php

namespace App\Models\Foodfleet;

use App\Models\Model;

/**
 * Class DocumentStatus
 * @package App\Models\Foodfleet
 *
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class DocumentStatus extends Model
{
    public function documents()
    {
        return $this->hasMany('Document', 'status');
    }
}
