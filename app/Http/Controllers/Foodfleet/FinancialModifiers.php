<?php


namespace App\Http\Controllers\Foodfleet;


use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Modifier as ModifierResource;
use App\Models\Foodfleet\FinancialModifier as Modifier;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class FinancialReportsModifiers
 * @package App\Http\Controllers\CSM
 */
class FinancialModifiers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ModifierResource::collection(QueryBuilder::for(Modifier::class, $request)->get());
    }
}