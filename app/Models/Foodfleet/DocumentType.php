<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    public function documents()
    {
        return $this->hasMany('Document', 'type');
    }
}
