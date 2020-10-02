<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\DocumentTemplate;
use App\Models\Foodfleet\DocumentTemplateStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentTemplateStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var DocumentTemplateStatus $status */
        $status = factory(DocumentTemplateStatus::class)->create();

        $this->assertDatabaseHas('document_template_statuses', [
            'id' => $status->id
        ]);

        // relations
        $template = factory(DocumentTemplate::class)->create([
            'status_id' => $status->id
        ]);
        $this->assertEquals($status->id, $template->type_id);
        $this->assertEquals($template->uuid, $status->templates->first()->uuid);
    }
}
