<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Document\Template\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var Template $template */
        $template = factory(Template::class)->create();

        $this->assertDatabaseHas('document_templates', [
            'uuid' => $template->uuid
        ]);

        // relations
        $this->assertEquals($template->status_id, $template->status->id);
    }
}
