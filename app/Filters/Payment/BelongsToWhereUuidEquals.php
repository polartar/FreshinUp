<?php


namespace App\Filters\Payment;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class BelongsToWhereUuidEquals implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas($property, function (Builder $query) use ($value) {
            $query->where('uuid', $value);
        });
    }
}
