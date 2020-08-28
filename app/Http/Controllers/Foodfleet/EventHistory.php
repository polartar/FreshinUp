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

        // TODO: should return a list of all statuses, not just the current status changes
        // if filter event_uuid is detected then apply this logic
         $event_uuid = $request->query('event_uuid');
         //for status in statuses
        $histories = [];
        $statuses = QueryBuilder::for(EventStatus::class, $request)->get();
        foreach ($statuses as $status) {
            $completed = false;
            $date = '';
            $description = $this->getEventStatusDescription($status->id);
            foreach ($eventHistories as $history){
                $completed = $history->status_id == $status->id ? $history->completed: $completed;
                $date = $history->status_id == $status->id ? $history->date->format('Y-m-d H:i:s') : $date;
                $description = $history->status_id == $status->id ? $history->description: $description;
            }
            array_push($histories,
                [
                    'id' => 1,
                    'event_uuid' => $event_uuid,
                    'status_id' => $status->id,
                    'name' => $status->name,
                    'completed' =>  $completed,
                    'date' =>  $date,
                    'description' =>  $description
                ]
            );

        }
        return JsonResource::make($histories);
    }
}
