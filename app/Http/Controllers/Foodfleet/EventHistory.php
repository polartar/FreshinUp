<?php

namespace App\Http\Controllers\Foodfleet;

use App\Enums\EventStatus as EventStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\EventHistory as EventHistoryResource;

class EventHistory extends Controller
{

    public static function getEventStatusDescription($id)
    {
        $eventStatusDescription = [
            EventStatusEnum::DRAFT => 'Event was created in the system and submitted for approval',
            EventStatusEnum::FF_INITIAL_REVIEW => 'Food Fleet Staff will review the event request',
            EventStatusEnum::CUSTOMER_AGREEMENT => 'Customer will review / sign event agreement and terms',
            EventStatusEnum::FLEET_MEMBER_SELECTION => 'FoodFleet will define event menu and identify interested Fleet Members and authorize work order',
            EventStatusEnum::CUSTOMER_REVIEW => 'Customer will review interested Fleet Members and authorize work order',
            EventStatusEnum::FLEET_MEMBER_CONTRACTS => 'Approved Fleet Members will review and sign event contracts',
        ];
        return $eventStatusDescription[$id] ?? '';
    }


    /**
     * @param Request $request
     * @return JsonResource
     */
    public function index(Request $request)
    {
        $eventHistories = QueryBuilder::for(\App\Models\Foodfleet\EventHistory::class, $request)
            ->allowedIncludes([
                'status',
            ])
            ->allowedFilters([
                Filter::exact('event_uuid')
            ])
            ->get();

        return JsonResource::make($eventHistories);
    }
}
