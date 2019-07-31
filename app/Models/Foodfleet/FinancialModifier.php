<?php

namespace App\Models\Foodfleet;

use Illuminate\Database\Eloquent\Model;

class FinancialModifier extends Model
{
    public $table = 'financial_modifiers';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
}
