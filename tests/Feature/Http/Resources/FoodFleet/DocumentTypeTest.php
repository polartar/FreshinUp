<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Document\Type as DocumentTypeResource;
use App\Models\Foodfleet\DocumentType as DocumentTypeModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class DocumentTypeTest extends TestCase {

    public function testResource () {
        $documentType = factory(DocumentTypeModel::class)->make();
        $resource = new DocumentTypeResource($documentType);
        $expected = [
            'value' => $documentType->id,
            'text' => $documentType->name,
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
