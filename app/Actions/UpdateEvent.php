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
        $updateData = $collection->except(['event_tags', 'store_uuids', 'uuid'])->all();
        $event->update($updateData);

        $tags = $collection->get('event_tags');
        if ($tags) {
            $tagIds =[];
            foreach ($tags as $tag) {
                $record = EventTag::firstOrCreate(['name' => $tag]);
                $tagUuids[] = $record->uuid;
            }

            $event->eventTags()->sync($tagUuids);
        }

        $storeUuids = $collection->get('store_uuids');
        $event->stores()->sync($storeUuids);
        return $event->refresh();
    }
}
