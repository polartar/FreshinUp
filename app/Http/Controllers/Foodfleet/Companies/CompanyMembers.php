<?php


namespace App\Http\Controllers\Foodfleet\Companies;

use App\User;
use App\Models\Foodfleet\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use FreshinUp\FreshBusForms\Http\Resources\User\User as UserResource;

class CompanyMembers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $users = QueryBuilder::for(
            User::whereIn('id', $company->users()->pluck('users.id')->toArray()),
            $request
        )
            ->allowedIncludes([
                'user_type',
                'user_level'
            ])
            ->allowedSorts([
                'first_name',
                'created_at'
            ])
            ->allowedFilters([
                'first_name',
                'last_name',
                'email'
            ]);

        return UserResource::collection($users->jsonPaginate());
    }
}
