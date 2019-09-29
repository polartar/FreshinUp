<?php


namespace App\Filters\Store;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TagUuid implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('tags', function (Builder $query) use ($value) {
            $query->where('uuid', $value);
        });
    }
}
