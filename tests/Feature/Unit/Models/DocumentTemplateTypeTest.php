<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\DocumentTemplate;
use App\Models\Foodfleet\DocumentTemplateType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentTemplateTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var DocumentTemplateType $type */
        $type = factory(DocumentTemplateType::class)->create();

        $this->assertDatabaseHas('document_template_types', [
            'id' => $type->id
        ]);

        // relations
        $template = factory(DocumentTemplate::class)->create([
            'type_id' => $type->id
        ]);
        $this->assertEquals($type->id, $template->type_id);
        $this->assertEquals($template->uuid, $type->templates->first()->uuid);
    }
}
