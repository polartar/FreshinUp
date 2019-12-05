<?php

namespace App\Http\Controllers\Foodfleet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Menu;
use App\Http\Resources\Foodfleet\Menu as MenuResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\DB;

class Menus extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Menu::class, $request)
            ->allowedIncludes(['store'])
            ->allowedFilters([
                'item',
                Filter::exact('uuid'),
                Filter::exact('store_uuid')
            ]);
        
        $searchTerm = null;
        if ($request->has('q')) {
            $searchTerm = $request->get('q');
        }
        if ($request->has('term')) {
            $searchTerm = $request->get('term');
        }
        if ($searchTerm) {
            $query->where(DB::raw('item'), 'LIKE', '%' . $searchTerm . '%');
        }

        return MenuResource::collection($query->jsonPaginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewRecommendation()
    {
        return new MenuResource(
            Menu::make()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'store_uuid' => 'string|required|exists:stores,uuid',
            'item' => 'string|required',
            'category' => 'string|required',
            'description' => 'string',
            'street_price' => 'integer|required'
        ]);

        $inputs = $request->input();

        $menu = Menu::create($inputs);

        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $uuid)
    {
        $menu = QueryBuilder::for(Menu::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([ 'store'])
            ->firstOrFail();

        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'store_uuid' => 'string|exists:stores,uuid',
            'item' => 'string',
            'category' => 'string',
            'description' => 'string',
            'street_price' => 'integer'
        ]);

        $inputs = $request->input();
        $menu = Menu::where('uuid', $uuid)->first();
        if ($menu) {
            $menu->update($inputs);
        }

        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $menu = Menu::where('uuid', $uuid)->firstOrFail();
        $menu->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
