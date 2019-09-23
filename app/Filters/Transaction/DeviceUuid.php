<?php


namespace App\Filters\Transaction;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DeviceUuid implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('payments', function (Builder $query) use ($value) {
            $query->where('device_uuid', $value);
        });
    }
}
