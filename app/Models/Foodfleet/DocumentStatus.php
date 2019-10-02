<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    public function documents()
    {
        return $this->hasMany('Document', 'status');
    }
}
