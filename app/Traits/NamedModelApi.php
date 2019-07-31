<?php

namespace App\Traits;

use App\Exceptions\MissingModelException;
use App\Http\Resources\Foodfleet\ResourceWithName;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

trait NamedModelApi
{

    /**
     * Return list of modelClass
     *
     * @param Request $request
     * @return ResourceWithName::collection
     * @throws MissingModelException
     */
    public function index(Request $request)
    {
        if (!isset($this->modelClass)) {
            throw new MissingModelException("Missing attribute 'modelClass' in Api controller");
        }

        $modelClassCollection = QueryBuilder::for($this->modelClass)
            ->allowedFilters('name')
            ->get();

        return ResourceWithName::collection($modelClassCollection);
    }
}
