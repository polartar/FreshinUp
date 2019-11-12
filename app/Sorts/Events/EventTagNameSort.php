<?php


namespace App\Sorts\Events;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Support\Facades\DB;

class EventTagNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $direction = $descending ? 'DESC' : 'ASC';
        return $query->select('*', DB::raw('
                (
                    SELECT group_concat( name )
                    FROM event_tags
                    JOIN events_event_tags
                    ON events_event_tags.event_tag_uuid = event_tags.uuid
                    WHERE events_event_tags.event_uuid = events.uuid
                )
                as tags_name
            '))
            ->orderByRaw("tags_name {$direction}");
    }
}
