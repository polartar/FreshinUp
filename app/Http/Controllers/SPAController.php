<?php

namespace App\Http\Controllers;

use FreshinUp\FreshBusForms\Http\Controllers\SPAController as BusSPA;

class SPAController extends BusSPA
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
