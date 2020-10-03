<?php

namespace Tests\Feature\Http\Resources\Foodfleet\Document\Template;

use App\Http\Resources\Foodfleet\Document\Template\Type as Resource;
use App\Models\Foodfleet\Document\Template\Type as Model;
use Illuminate\Http\Request;
use Tests\TestCase;

class TypeTest extends TestCase {

    public function testResource () {
        $item = factory(Model::class)->make();
        $resource = new Resource($item);
        $expected = [
            'id' => $item->id,
            'name' => $item->name,
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
