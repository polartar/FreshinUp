<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use App\Helpers\EventScheduleHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\EventSchedule;
use App\Models\Foodfleet\EventOccurrence;

class CreateEvent implements Action
{
    public function execute(array $data)
    {
        $collection = collect($data);
        $createData = $collection->except(['event_tags', 'schedule'])->all();
        $event = Event::create($createData);

        $tags = $collection->get('event_tags');

        if ($tags) {
            $tagIds =[];
            foreach ($tags as $tag) {
                $record = EventTag::firstOrCreate(['name' => $tag]);
                $tagUuids[] = $record->uuid;
            }

            $event->eventTags()->sync($tagUuids);
        }

        $this->handleSchedule($event, $collection);

        return $event->refresh();
    }

    private function handleSchedule(Event $event, $collection)
    {
        $schedule = collect($collection->get('schedule'));
        $interval_unit = $schedule->get('interval_unit');
        $interval_value = $schedule->get('interval_value');
        $occurrences = $schedule->get('occurrences');
        $ends_on = $schedule->get('ends_on');
        $repeat_on = $schedule->get('repeat_on');
        $description = $schedule->get('description');
        if ((empty($interval_unit) || empty($interval_value) || empty($occurrences) ||
            empty($ends_on) || empty($description)) || empty($repeat_on) && $interval_unit != 'Year(s)') {
            return;
        }
        
        $schedule = new EventSchedule;
        $schedule->event_uuid = $event->uuid;
        $schedule->interval_unit = $interval_unit;
        $schedule->interval_value = $interval_value;
        $schedule->occurrences = $occurrences;
        $schedule->ends_on = $ends_on;
        $schedule->repeat_on = json_encode($repeat_on);
        $schedule->description = $description;
        $schedule->save();

        $schedule_periods = EventScheduleHelper::analyzeSchedule($schedule);
        foreach ($schedule_periods as $value) {
            $occurrence = new EventOccurrence;
            $occurrence->event_schedule_uuid = $schedule->uuid;
            $occurrence->start_at = $value->start_at;
            $occurrence->end_at = $value->end_at;
            $occurrence->save();
        }
    }
}
