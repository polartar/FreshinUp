<?php


namespace App\Sorts\Events;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class ManagerNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $direction = $descending ? 'DESC' : 'ASC';
        return $query->join('users', 'events.manager_uuid', '=', 'users.uuid')
            ->select('events.*')
            ->orderByRaw("users.first_name {$direction}");
    }
}
