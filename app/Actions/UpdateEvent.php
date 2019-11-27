<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;

class UpdateEvent implements Action
{
    public function execute(array $data)
    {
        $event = Event::where('uuid', $data['uuid'])->first();

        $collection = collect($data);
        $updateData = $collection->except(['event_tags', 'uuid', 'host', 'manager'])->all();
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
        }
        return $event->refresh();
    }
}
