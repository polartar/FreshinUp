<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\Document\Type as DocumentTypeResource;
use App\Models\Foodfleet\DocumentType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentTypes extends Controller
{
    public function index(Request $request)
    {
        $documentTypes = QueryBuilder::for(DocumentType::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return DocumentTypeResource::collection($documentTypes);
    }
}
