<?php


namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Document as DocumentResource;
use App\Http\Resources\Foodfleet\DocumentStatus;
use App\Http\Resources\Foodfleet\DocumentType;
use App\Models\Foodfleet\Document;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use WithFaker;

    public function testResource()
    {
        $document = factory(Document::class)->create();
        $document->load(['type', 'status', 'template']);
        $resource = new DocumentResource($document);
        $expected = [
            'id' => $document->id,
            'uuid' => $document->uuid,
            'title' => $document->title,
            'status_id' => $document->status_id,
            'type_id' => $document->type_id,
            'description' => $document->description,
            'notes' => $document->notes,
            'expiration_at' => $document->expiration_at,
            'created_by_uuid' => $document->created_by_uuid,
            'event_store_uuid' => $document->event_store_uuid,
            'template_uuid' => $document->template_uuid,
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);

        $this->assertArrayHasKey('type', $result);
        $this->assertArraySubset(
            (new DocumentType($document->type))->toArray($request),
            $result['type']->toArray($request)
        );

        $this->assertArrayHasKey('status', $result);
        $this->assertArraySubset(
            (new DocumentStatus($document->status))->toArray($request), $result['status']->toArray($request)
        );

        $this->assertArrayHasKey('template', $result);
        $this->assertArraySubset(
            (new DocumentResource\Template\Template($document->template))->toArray($request), $result['template']->toArray($request)
        );
    }
}
