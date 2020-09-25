<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\DocumentStatus as DocumentStatusResource;
use App\Models\Foodfleet\DocumentStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentStatuses extends Controller
{
    public function index(Request $request)
    {
        $documentStatuses = QueryBuilder::for(DocumentStatus::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return DocumentStatusResource::collection($documentStatuses);
    }
}
