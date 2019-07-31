<?php


namespace App\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\PaymentType;
use App\Traits\NamedModelApi;

class PaymentTypes
{
    use NamedModelApi;

    protected $modelClass = PaymentType::class;
}
