<?php

namespace App\Sorts\Stores;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class StoreTagNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $direction = $descending ? 'DESC' : 'ASC';
        return $query->select('*', DB::raw('
                (
                    SELECT group_concat( name )
                    FROM store_tags
                    JOIN stores_store_tags
                    ON stores_store_tags.store_tag_uuid = store_tags.uuid
                    WHERE stores_store_tags.store_tag_uuid = stores.uuid
                )
                as tags_name
            '))
            ->orderByRaw("tags_name {$direction}");
    }
}
