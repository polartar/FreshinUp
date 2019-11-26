<?php


namespace App\Sorts\Events;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class HostNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $direction = $descending ? 'DESC' : 'ASC';
        return $query->join('companies', 'events.host_uuid', '=', 'companies.uuid')
            ->select('events.*')
            ->orderByRaw("companies.name {$direction}");
    }
}
