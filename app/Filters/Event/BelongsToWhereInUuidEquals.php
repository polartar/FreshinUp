<?php


namespace App\Filters\Event;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class BelongsToWhereInUuidEquals implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas($property, function (Builder $query) use ($value) {
            $query->whereIn('uuid', $value);
        });
    }
}
