<?php

namespace App;

use App\Models\Foodfleet\FinancialReport;

class User extends \FreshinUp\FreshBusForms\Models\User\User
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data_visibility' => 'array'
    ];

    public function getMorphClass()
    {
        return 'FreshinUp\FreshBusForms\Models\User\User';
    }

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class);
    }
}
