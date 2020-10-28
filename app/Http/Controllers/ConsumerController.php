<?php

namespace App\Http\Controllers;

use FreshinUp\FreshBusForms\Http\Controllers\ConsumerController as BusConsumer;

class ConsumerController extends BusConsumer
{
    /**
     * Display a listing of Team.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('app');
    }
}
