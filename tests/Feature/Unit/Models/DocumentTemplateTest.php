<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\DocumentTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var DocumentTemplate $template */
        $template = factory(DocumentTemplate::class)->create();

        $this->assertDatabaseHas('document_templates', [
            'uuid' => $template->uuid
        ]);

        // relations
        $this->assertEquals($template->type_id, $template->type->id);
        $this->assertEquals($template->status_id, $template->status->id);
    }
}
