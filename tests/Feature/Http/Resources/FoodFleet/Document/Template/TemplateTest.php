<?php

namespace Tests\Feature\Http\Resources\Foodfleet\Document\Template;

use App\Http\Resources\Foodfleet\Document\Template\Template as Resource;
use App\Models\Foodfleet\Document\Template\Template as Model;
use Illuminate\Http\Request;
use Tests\TestCase;

class TemplateTest extends TestCase {

    public function testResource () {
        $item = factory(Model::class)->create();
        $resource = new Resource($item);
        $expected = [
            'id' => $item->id,
            'uuid' => $item->uuid,
            'title' => $item->title,
            'status_id' => $item->status_id,
        ];
        $request = app()->make(Request::class);
        $this->assertArraySubset($expected, $resource->toArray($request));
    }
}
