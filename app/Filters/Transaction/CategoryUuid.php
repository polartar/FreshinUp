<?php


namespace App\Filters\Transaction;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CategoryUuid implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('items', function (Builder $query) use ($value) {
            $query->where('category_uuid', $value);
        });
    }
}
