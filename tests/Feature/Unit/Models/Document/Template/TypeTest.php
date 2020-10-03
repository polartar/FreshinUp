<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\Document\Template\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TypeTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var Type $type */
        $type = factory(Type::class)->create();

        $this->assertDatabaseHas('document_template_types', [
            'id' => $type->id
        ]);

        // relations
        $template = factory(Template::class)->create([
            'type_id' => $type->id
        ]);
        $this->assertEquals($type->id, $template->type_id);
        $this->assertEquals($template->uuid, $type->templates->first()->uuid);
    }
}
