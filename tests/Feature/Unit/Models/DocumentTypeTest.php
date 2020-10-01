<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\DocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $type = factory(DocumentType::class)->create();

        $this->assertDatabaseHas('document_types', [
            "id" => $type->id,
            "name" => $type->name,
        ]);

        $document = factory(Document::class)->create([
            'type_id' => $type->id
        ]);
        $this->assertEquals($document->uuid, $type->documents->first()->uuid);
    }
}
