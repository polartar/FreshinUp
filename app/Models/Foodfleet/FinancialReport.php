<?php

namespace App\Models\Foodfleet;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialReport extends Model
{
    use SoftDeletes;

    public $table = 'financial_reports';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'filters' => 'array',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function modifier1()
    {
        return $this->belongsTo(FinancialModifier::class, 'modifier_1_id');
    }

    public function modifier2()
    {
        return $this->belongsTo(FinancialModifier::class, 'modifier_2_id');
    }
}
