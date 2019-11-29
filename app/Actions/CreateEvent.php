<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;

class CreateEvent implements Action
{
    public function execute(array $data)
    {
        $collection = collect($data);
        $createData = $collection->except(['event_tags'])->all();
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
        return $event->refresh();
    }
}
