<?php
namespace App\Models\Foodfleet;
use Illuminate\Database\Eloquent\Model;
class StoreStatus extends Model
{
    public function stores()
    {
        return $this->hasMany('Store', 'status');
    }
}
