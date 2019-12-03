<?php

namespace App\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Company;
use FreshinUp\FreshBusForms\Http\Resources\User\UserCollection;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyOwners extends Controller
{
    public function index(Request $request)
    {
        $companyOwners = QueryBuilder::for(User::class, $request)
            ->allowedFilters(['last_name', 'first_name'])
            ->whereIn('id', Company::pluck('users_id')->toArray())
            ->get();

        return new UserCollection($companyOwners);
    }
}
