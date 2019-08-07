<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Staff;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Staff as StaffResource;
use Illuminate\Support\Facades\DB;

class Staffs extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $staffs = QueryBuilder::for(Staff::class, $request)
            ->allowedFilters([
                'square_id'
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
                $staffs->where(DB::raw('first_name || " " || last_name'), 'LIKE', '%' . $searchTerm . '%');
            } else {
                $staffs->where(DB::raw('CONCAT(" ", first_name, last_name)'), 'LIKE', '%' . $searchTerm . '%');
            }
        }

        return StaffResource::collection($staffs->jsonPaginate());
    }
}
