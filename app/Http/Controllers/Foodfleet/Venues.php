<?php


namespace App\Http\Controllers\Foodfleet;

use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Venue as VenueResource;

class Venues extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'owner_uuid' => 'exists:users,uuid',
            'status_id' => 'exists:venue_statuses,id',
        ]);
        $payload = $request->only([
            'name',
            'address_line_1',
            'address_line_2',
            'latitude',
            'longitude',
            'status_id',
            'owner_uuid',
        ]);
        $venue = Venue::create($payload);
        $venue->load('owner');
        return new VenueResource($venue);
    }

    public function index(Request $request)
    {
        $venues = QueryBuilder::for(Venue::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name',
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('owner_uuid', BelongsToWhereInUuidEquals::class, 'owner')
            ])
            ->allowedIncludes([
                'locations',
                'owner',
                'status'
            ])
            ->allowedSorts([
                'name',
                'status_id',
                'owner_uuid',
                'created_at'
            ]);
        return VenueResource::collection($venues->jsonPaginate());
    }

    public function update(Request $request, $uuid)
    {
        $venue = Venue::where('uuid', $uuid)->firstOrFail();
        $this->validate($request, [
            'owner_uuid' => 'exists:users,uuid',
            'status_id' => 'exists:venue_statuses,id',
        ]);
        $payload = $request->only([
            'name',
            'address_line_1',
            'address_line_2',
            'latitude',
            'longitude',
            'status_id',
            'owner_uuid',
        ]);
        $venue->update($payload);
        $venue->load('owner');
        return new VenueResource($venue);
    }

    public function destroy($uuid)
    {
        $venue = Venue::where('uuid', $uuid)->firstOrFail();
        $venue->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function show(Request $request, $uuid)
    {
        $venue = QueryBuilder::for(Venue::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([
                'locations',
                'owner',
                'status'
            ])
            ->firstOrFail();
        return new VenueResource($venue);
    }
}
