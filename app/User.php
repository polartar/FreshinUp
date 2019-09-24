<?php

namespace App;

use App\Models\Foodfleet\FinancialReport;
use App\Models\Foodfleet\Document;

class User extends \FreshinUp\FreshBusForms\Models\User\User
{
    public function getMorphClass()
    {
        return 'FreshinUp\FreshBusForms\Models\User\User';
    }

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }
}
