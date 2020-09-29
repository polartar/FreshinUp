<?php


namespace App\Http\Controllers\Foodfleet;

use App\Actions\CreateDocument;
use App\Enums\DocumentAssigned;
use App\Enums\DocumentStatus;
use App\Enums\DocumentTypes as DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Location as LocationResource;
use App\Models\Foodfleet\Location as LocationModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class Locations extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $locations = QueryBuilder::for(LocationModel::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                Filter::exact('venue_uuid'),
                Filter::exact('category_id'),
                'name',
            ])
            ->allowedSorts([
                'name',
                'capacity'
            ])
            ->allowedIncludes([
                'venue', 'category', 'events'
            ]);

        return LocationResource::collection($locations->jsonPaginate());
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'spots' => 'nullable|integer',
            'capacity' => 'nullable|integer',
            'details' => 'nullable|string',
            'venue_uuid' => 'required|exists:venues,uuid',
            'category_id' => 'required|exists:location_categories,id'
        ];
        $this->validate($request, array_merge(
            $rules,
            [
            'files' => 'nullable|array'
            ]
        ));
        $payload = $request->only(array_keys($rules));
        $location = LocationModel::create($payload);
        if ($request->has('files')) {
            foreach ($request->input('files') as $file) {
                $action = new CreateDocument();
                $action->execute([
                    'title' => $file['name'],
                    'description' => $file['name'],
                    'file' => $file,
                    'status' => DocumentStatus::PENDING,
                    'type' => DocumentTypeEnum::DOWNLOADABLE,
                    'assigned_type' => DocumentAssigned::LOCATION,
                    'assigned_uuid' => $location->uuid,
                    'owner_uuid' => optional($request->user())->id
                ]);
            }
        }
        return new LocationResource($location);
    }

    public function destroy($uuid)
    {
        $venue = LocationModel::where('uuid', $uuid)->firstOrFail();
        $venue->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
