<?php


namespace App\Http\Controllers\Foodfleet\Events;

use App\Http\Controllers\Controller;
use App\Actions\CreateEvent;
use App\Actions\UpdateEvent;
use App\Models\Foodfleet\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\Sort;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Enums\EventType as EventTypeEnum;
use App\Http\Resources\Foodfleet\EventSummary as EventSummaryResource;
use App\Enums\EventStatus as EventStatusEnum;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToManyWhereInUuidEquals;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo;
use App\Sorts\Events\HostNameSort;
use App\Sorts\Events\ManagerNameSort;
use App\Sorts\Events\EventTagNameSort;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
        $user = $request->user();
        $isAdmin = $user->isAdmin();
        $requestFilters = $request->get('filter', []);
        if (!$isAdmin && $user->type == EventTypeEnum::CATERING) {
            if (array_key_exists('manager_uuid', $requestFilters)) {
                $requestFilters['manager_uuid'] = $requestFilters['manager_uuid'] . ',' . $user->uuid;
            } else {
                $requestFilters['manager_uuid'] = $user->uuid;
            }
            $request->query->add(['filter' => $requestFilters]);
        }

        if (!$isAdmin && $user->type == EventTypeEnum::CASH_AND_CARRY) {
            if (array_key_exists('host_uuid', $requestFilters)) {
                $requestFilters['host_uuid'] = $requestFilters['host_uuid'] . ',' . $user->uuid;
            } else {
                $requestFilters['host_uuid'] = $user->uuid;
            }
            $request->query->add(['filter' => $requestFilters]);
        }

        $events = QueryBuilder::for(Event::class, $request)
            ->with('stores')
            ->allowedIncludes([
                'status',
                'host',
                'location',
                'location.venue',
                'manager',
                'event_tags',
                'type',
                'venue'
            ])
            ->allowedSorts([
                'name',
                'start_at',
                'status_id',
                'type_id',
                Sort::custom('host', new HostNameSort()),
                Sort::custom('manager', new ManagerNameSort()),
                Sort::custom('event_tags', new EventTagNameSort()),
            ])
            ->allowedFilters([
                'name',
                Filter::exact('uuid'),
                Filter::custom('start_at', GreaterThanOrEqualTo::class, 'start_at'),
                Filter::custom('end_at', LessThanOrEqualTo::class, 'end_at'),
                Filter::custom('host_uuid', BelongsToWhereInUuidEquals::class, 'host'),
                Filter::custom('manager_uuid', BelongsToWhereInUuidEquals::class, 'manager'),
                Filter::custom('store_uuid', BelongsToManyWhereInUuidEquals::class, 'stores'),
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('event_tag_uuid', BelongsToWhereInUuidEquals::class, 'eventTags'),
                Filter::custom('type_id', BelongsToWhereInIdEquals::class, 'type'),
                Filter::custom('venue_uuid', BelongsToWhereInUuidEquals::class, 'venue'),
                Filter::custom('location_uuid', BelongsToWhereInUuidEquals::class, 'location'),
            ])
            ->jsonPaginate();
        return EventResource::collection($events);
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
            Event::make(
                ([
                'status_id' => EventStatusEnum::DRAFT
                ])
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  CreateEvent  $action
     * @return EventResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CreateEvent $action)
    {
        $request = $this->prepareDatesForValidation($request);
        if ($request->get('status_id') == EventStatusEnum::DRAFT) {
            $validationRules = ['name' => 'required'];
        } else {
            $validationRules = [
                'name' => 'required',
                'manager_uuid' => 'string|required|exists:users,uuid',
                'host_uuid' => 'string|required|exists:companies,uuid',
                'location_uuid' => 'string|exists:locations,uuid',
                'status_id' => 'integer|required',
                'start_at' => 'date|required|after:now',
                'end_at' => 'date|required|after:start_at',
                'staff_notes' => 'string',
                'member_notes' => 'string',
                'customer_notes' => 'string',
                'commission_rate' => 'integer|required',
                'commission_type' => 'integer|required',
                'schedule.interval_unit' => 'string',
                'schedule.interval_value' => 'integer',
                'schedule.occurrences' => 'integer',
                'schedule.ends_on' => 'string',
                'schedule.repeat_on' => 'array',
                'schedule.description' => 'string'
            ];
        }

        $this->validate($request, $validationRules);

        $inputs = $request->input();
        $event = $action->execute($inputs);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param $uuid
     * @return EventResource
     */
    public function show(Request $request, $uuid)
    {
        $event = QueryBuilder::for(Event::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([ 'manager', 'host', 'location', 'event_tags', 'stores', 'type', 'venue' ])
            ->firstOrFail();

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $uuid
     * @param  UpdateEvent  $action
     * @return EventResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $uuid, UpdateEvent $action)
    {
        $request = $this->prepareDatesForValidation($request);
        $this->validate($request, [
            'name' => 'string',
            'manager_uuid' => 'string|exists:users,uuid',
            'host_uuid' => 'string|exists:companies,uuid',
            'location_uuid' => 'string|exists:locations,uuid',
            'venue_uuid' => 'string|exists:venues,uuid',
            'store_uuids' => 'array',
            'store_uuids.*' => 'string|exists:stores,uuid',
            'status_id' => 'integer',
            'start_at' => 'date',
            'end_at' => 'date|after:start_at',
            'staff_notes' => 'string',
            'member_notes' => 'string',
            'customer_notes' => 'string',
            'commission_rate' => 'integer',
            'commission_type' => 'integer',
            'schedule.interval_unit' => 'string',
            'schedule.interval_value' => 'integer',
            'schedule.occurrences' => 'integer',
            'schedule.ends_on' => 'string',
            'schedule.repeat_on' => 'array',
            'schedule.description' => 'string'
        ]);

        $inputs = $request->input();
        $inputs['uuid'] = $uuid;

        $event = $action->execute($inputs);

        return new EventResource($event);
    }

    public function summary(Request $request, $uuid)
    {
        $event = QueryBuilder::for(Event::class, $request)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new EventSummaryResource($event);
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
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $event->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }

    public function duplicate(Request $request, $uuid)
    {
        $this->validate($request, [
            'basicInformation' => 'required|boolean',
            'venue' => 'boolean',
            'customer' => 'boolean',
            'fleetMember' => 'boolean'
        ]);
        $fields = $request->only(['basicInformation', 'venue', 'customer', 'fleetMember']);
        if (!$fields['basicInformation']) {
            return response([
                'basicInformation' => 'Basic information is required at least'
            ], 422);
        }
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $payload = [];
        // Basic Information
        if (Arr::get($fields, 'basicInformation', false)) {
            $payload = array_merge($payload, [
                'name' => "Copy of $event->name",
                'type_id' => $event->type_id,
                'start_at' => $event->start_at,
                'end_at' => $event->end_at,
                'host_uuid' => $event->host_uuid,
                'host_status' => $event->host_status,
                'manager_uuid' => $event->manager_uuid,
                'status_id' => $event->status_id,
                'budget' => $event->budget,
                'attendees' => $event->attendees,
                'commission_rate' => $event->commission_rate,
                'commission_type' => $event->commission_type,
                'staff_notes' => $event->staff_notes,
                'member_notes' => $event->member_notes,
                'customer_notes' => $event->customer_notes,
            ]);
        }

        // Venue
        if (Arr::get($fields, 'venue', false)) {
            $payload = array_merge($payload, [
                'venue_uuid' => $event->venue_uuid,
                'location_uuid' => $event->location_uuid,
            ]);
        }

        // Customer
        if (Arr::get($fields, 'customer', false)) {
            $payload = array_merge($payload, [
                'host_uuid' => $event->host_uuid,
            ]);
        }
        $duplicate = Event::create($payload);

        // Fleet member
        if (Arr::get($fields, 'fleetMember', false)) {
            $storeUuids = $event->stores()->pluck('uuid');
            $duplicate->stores()->sync($storeUuids);
        }

        if (Arr::get($fields, 'basicInformation', false)) {
            $tagUuids = $event->eventTags()->pluck('uuid');
            $duplicate->eventTags()->sync($tagUuids);
        }
        return new EventResource($duplicate);
    }

    protected function prepareDatesForValidation(Request $request)
    {
        $dates = ['start_at', 'end_at'];

        $updated_dates = [];

        foreach ($dates as $dt) {
            if ($request->has($dt)) {
                //check the length
                //NB: 2020-12-23 is in the format of Y-m-d and has a length of 10
                //NB: 2020-12-24 00:00 is in the format of Y-m-d H:i without :s and has a length of 16
                $param = $request->get($dt);
                if (($len = strlen($param)) == 16 || $len == 27) {
                    $new_date = Carbon::parse($param)->toDateTimeString();

                    $updated_dates[$dt] = $new_date;
                }
            }
        }

        if (count($updated_dates) > 0) {
            //at least one of the two dates was updated.

            $request->merge($updated_dates);
        }

        return $request;
    }
}
