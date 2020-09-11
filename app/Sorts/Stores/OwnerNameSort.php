<?php


namespace App\Sorts\Stores;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class OwnerNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        $direction = $descending ? 'DESC' : 'ASC';
        return $query->join('users', 'stores.owner_uuid', '=', 'users.uuid')
            ->select('stores.*')
            ->orderByRaw("users.first_name {$direction}");
    }
}
