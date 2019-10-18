<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Actions\CreateEvent;
use App\Actions\UpdateEvent;
use App\Models\Foodfleet\Event;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Event as EventResource;

class Events extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $events = QueryBuilder::for(Event::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return EventResource::collection($events->jsonPaginate());
    }

    /**
     * Generates an empty event.
     *
     * @return EventResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showNewRecommendation()
    {
        return new EventResource(
            Event::make()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return EventResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CreateEvent $action)
    {
        $this->validate($request, [
            'name' => 'required',
            'host_uuid' => 'string|required|exists:companies,uuid',
            'location_uuid' => 'string|exists:locations,uuid',
            'status_id' => 'integer|required',
            'start_at' => 'date|required',
            'end_at' => 'date|required|after:start_date',
            'commission_rate' => 'integer|required',
            'commission_type' => 'integer|required'
        ]);

        $inputs = $request->input();
        $event = $action->execute($inputs);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param $uuid
     * @return EventResource
     */
    public function show(Request $request, $uuid)
    {
        $event = QueryBuilder::for(Event::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([ 'host', 'location', 'event_tags' ])
            ->first();

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $uuid
     * @return EventResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $uuid, UpdateEvent $action)
    {
        $this->validate($request, [
            'name' => 'string',
            'manager_uuid' => 'string|exists:users,uuid',
            'host_uuid' => 'string|exists:companies,uuid',
            'status_id' => 'integer',
            'start_at' => 'date',
            'end_at' => 'date',
            'commission_rate' => 'integer',
            'commission_type' => 'integer'
        ]);

        $inputs = $request->input();
        $inputs['uuid'] = $uuid;

        $event = $action->execute($inputs);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($uuid)
    {
        $event = Event::where('uuid', $uuid)->first();
        $event->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
