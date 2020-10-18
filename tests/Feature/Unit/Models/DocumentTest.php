<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $type = factory(Document::class)->create();

        $this->assertDatabaseHas('documents', [
            'id' => $type->id,
            'uuid' => $type->uuid,
            'title' => $type->title,
            'status_id' => $type->status_id,
            'type_id' => $type->type_id,
            'description' => $type->description,
            'notes' => $type->notes,
            'expiration_at' => $type->expiration_at,
            'created_by_uuid' => $type->created_by_uuid,
            'created_at' => $type->created_at,
            'updated_at' => $type->updated_at,
            'deleted_at' => $type->deleted_at,
            'assigned_uuid' => $type->assigned_uuid,
            'assigned_type' => $type->assigned_type,
            'event_store_uuid' => $type->event_store_uuid,
            'template_uuid' => $type->template_uuid,
        ]);
    }

    public function testOwner()
    {
        $document = factory(Document::class)->make();
        $this->assertEquals($document->created_by_uuid, $document->owner->uuid);
    }

    public function testStatus()
    {
        $document = factory(Document::class)->make();
        $this->assertEquals($document->status_id, $document->status->id);
    }

    public function testType()
    {
        $document = factory(Document::class)->make();
        $this->assertEquals($document->type_id, $document->type->id);
    }

    public function testAssigned()
    {
        $assignedTypes = [
            \App\User::class,
            \App\Models\Foodfleet\Event::class,
            \App\Models\Foodfleet\Store::class,
            \App\Models\Foodfleet\Location::class,
        ];
        $assigned_type = $this->faker->randomElement($assignedTypes);
        $document = factory(Document::class)->make([
            'assigned_type' => $assigned_type,
            'assigned_uuid' => factory($assigned_type)->create()->uuid
        ]);
        $this->assertEquals($document->assigned_uuid, $document->assigned->uuid);
        $this->assertEquals($document->assigned_type, get_class($document->assigned));
    }

    public function testTemplate()
    {
        $document = factory(Document::class)->make();
        $this->assertEquals($document->template_uuid, $document->template->uuid);
    }
}
