<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Customer;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Customer as CustomerResource;
use Illuminate\Support\Facades\DB;

class Customers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $customers = QueryBuilder::for(Customer::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'square_id',
                'reference_id'
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
                $customers->where(DB::raw('given_name || " " || family_name'), 'LIKE', '%' . $searchTerm . '%');
            } else {
                $customers->where(DB::raw('CONCAT(" ", given_name, family_name)'), 'LIKE', '%' . $searchTerm . '%');
            }
        }

        return CustomerResource::collection($customers->jsonPaginate());
    }
}
