<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\Document\Template\Status as Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var Model $item */
        $item = factory(Model::class)->create();

        $this->assertDatabaseHas('document_template_statuses', [
            'id' => $item->id
        ]);

        // relations
        $template = factory(Template::class)->create([
            'status_id' => $item->id
        ]);
        $this->assertEquals($item->id, $template->status_id);
        $this->assertEquals($template->uuid, $item->templates->first()->uuid);
    }
}
