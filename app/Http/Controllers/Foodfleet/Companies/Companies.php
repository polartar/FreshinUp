<?php

namespace App\Http\Controllers\Foodfleet\Companies;

use App\Models\Foodfleet\Company;
use App\Http\Resources\Foodfleet\Company as CompanyResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filters\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Freshinp\FreshBusForms\Filters\Company\Term as FilterTerm;
use Freshinp\FreshBusForms\Filters\Company\TypeKey as FilterTypeKey;

class Companies extends \FreshinUp\FreshBusForms\Http\Controllers\API\Companies\Companies {
    public function index(Request $request)
    {
        $requestFilters = $request->get('filter', []);
        if ($request->has('q') && !$request->has('filter[q]')) {
            $requestFilters['q'] = $request->get('q');
            $request->merge(['filter' => $requestFilters]);
        }
        if ($request->has('status') && !$request->has('filter[status]')) {
            $requestFilters['status'] = $request->get('status');
            $request->merge(['filter' => $requestFilters]);
        }
        // if FR admin bring back pending companies
        $companies = QueryBuilder::for(Company::class, $request)
            ->defaultSort('name')
            ->allowedIncludes(['users', 'teams', 'type'])
            ->allowedFilters([
                Filter::exact('id'),
                Filter::exact('uuid'),
                'name',
                'status',
                'users_id',
                Filter::custom('q', FilterTerm::class),
                Filter::custom('type', FilterType::class),
                Filter::custom('type_key', FilterTypeKey::class),
            ])
            ->withCount('users')
            ->allowedSorts('name', 'created_at', 'users_count');
        return CompanyResource::collection($companies->jsonPaginate());
    }
}
