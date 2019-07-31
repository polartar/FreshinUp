<?php


namespace App\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Device;
use App\Traits\NamedModelApi;

class Devices
{
    use NamedModelApi;

    protected $modelClass = Device::class;
}
