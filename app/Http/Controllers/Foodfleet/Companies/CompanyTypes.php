<?php


namespace App\Http\Controllers\Foodfleet\Companies;

use App\Http\Controllers\Controller;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyTypes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $companyTypes = QueryBuilder::for(CompanyType::class, $request)
            ->jsonPaginate();

        return JsonResource::collection($companyTypes);
    }
}
