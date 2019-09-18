<?php


namespace App\Filters\Transaction;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SupplierUuid implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('event.stores', function (Builder $query) use ($value) {
            $query->where('supplier_uuid', $value);
        });
    }
}
