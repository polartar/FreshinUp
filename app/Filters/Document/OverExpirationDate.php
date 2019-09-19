<?php

namespace App\Filters\Document;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OverExpirationDate implements Filter
{
    public function __invoke(Builder $query, $value, $date) : Builder
    {
        return $query->where('expiration_at', '<=', Carbon::createFromFormat('Y-m-d', $value));
    }
}
