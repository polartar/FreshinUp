<?php


namespace App\Http\Controllers\Foodfleet\Companies;

use App\User;
use App\Models\Foodfleet\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use FreshinUp\FreshBusForms\Http\Resources\User\User as UserResource;
use Illuminate\Support\Facades\DB;

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

        $query = QueryBuilder::for(
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
        
        $searchTerm = null;
        if ($request->has('q')) {
            $searchTerm = $request->get('q');
        }
        if ($request->has('term')) {
            $searchTerm = $request->get('term');
        }
        if ($searchTerm) {
            if (DB::getDriverName() === 'sqlite') {
                $query->where(DB::raw('first_name || " " || last_name'), 'LIKE', '%' . $searchTerm . '%');
            } else {
                $query->where(DB::raw('CONCAT(" ", first_name, last_name)'), 'LIKE', '%' . $searchTerm . '%');
            }
        }

        return UserResource::collection($query->jsonPaginate());
    }
}
