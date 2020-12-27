<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use Illuminate\Support\Facades\DB;
use App\Helpers\EventScheduleHelper;
use App\Models\Foodfleet\EventSchedule;
use App\Models\Foodfleet\EventOccurrence;
use FreshinUp\FreshBusForms\Actions\Action;

class UpdateEvent implements Action
{
    public function execute(array $data)
    {
        /** @var Event $event */
        $event = Event::where('uuid', $data['uuid'])->first();

        $collection = collect($data);
        $updateData = $collection->except(['event_tags', 'store_uuids', 'uuid', 'schedule'])->all();
        $event->update($updateData);

        $tags = $collection->get('event_tags');
        if ($tags) {
            foreach ($tags as $tag) {
                if (!empty($tag['uuid'])) {
                    $tagUuids[] = $tag['uuid'];
                } else {
                    $record = EventTag::firstOrCreate(['name' => $tag]);
                    $tagUuids[] = $record->uuid;
                }
            }
            $event->eventTags()->sync($tagUuids);
        } elseif ($collection->has('event_tags')) {
            $event->eventTags()->sync([]);
        }

        $storeUuids = $collection->get('store_uuids');
        if (empty($storeUuids) || $storeUuids) {
            $event->stores()->sync($storeUuids);
        }

        $this->handleSchedule($event, $collection);

        return $event->refresh();
    }

    private function handleSchedule(Event $event, $collection)
    {
        $request = collect($collection->get('schedule'));
        $schedule = EventSchedule::where('event_uuid', $event->uuid)->first();
        if (count($request) == 0 && !empty($schedule)) {
            $schedule->scheduleOccurrences()->delete();
            $schedule->delete();
            return;
        }

        $interval_unit = $request->get('interval_unit');
        $interval_value = $request->get('interval_value');
        $occurrences = $request->get('occurrences');
        $ends_on = $request->get('ends_on');
        $repeat_on = $request->get('repeat_on');
        $description = $request->get('description');
        if ((empty($interval_unit) || empty($interval_value) || empty($occurrences) && $ends_on == 'after' ||
            empty($ends_on) || empty($description)) || empty($repeat_on) && $interval_unit != 'Year(s)') {
            return;
        }

        if (empty($schedule)) {
            $schedule = new EventSchedule;
        } else {
            $schedule->scheduleOccurrences()->delete();
        }
        $schedule->event_uuid = $event->uuid;
        $schedule->interval_unit = $interval_unit;
        $schedule->interval_value = $interval_value;
        $schedule->occurrences = $occurrences;
        $schedule->ends_on = $ends_on;
        $schedule->repeat_on = json_encode($repeat_on);
        $schedule->description = $description;
        $schedule->save();

        $schedule_periods = EventScheduleHelper::analyzeSchedule(
            $schedule,
            $event->start_at,
            $event->end_at
        );
        foreach ($schedule_periods as $value) {
            $occurrence = new EventOccurrence;
            $occurrence->event_schedule_uuid = $schedule->uuid;
            $occurrence->start_at = $value->start_at;
            $occurrence->end_at = $value->end_at;
            $occurrence->save();
        }
    }
}
