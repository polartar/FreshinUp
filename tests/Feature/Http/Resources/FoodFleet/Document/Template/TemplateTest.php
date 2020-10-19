<?php

namespace Tests\Feature\Http\Resources\Foodfleet\Document\Template;

use App\Http\Resources\Foodfleet\Document\Template\Template as Resource;
use App\Models\Foodfleet\Document\Template\Template as Model;
use FreshinUp\FreshBusForms\Http\Resources\User\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class TemplateTest extends TestCase {

    public function testResource () {
        /** @var Model $item */
        $item = factory(Model::class)->create();
        $item->load(['updatedBy']);
        $resource = new Resource($item);
        $expected = [
            'id' => $item->id,
            'uuid' => $item->uuid,
            'title' => $item->title,
            'content' => $item->content,
            'description' => $item->description,
            'status_id' => $item->status_id,
            'updated_by_uuid' => $item->updated_by_uuid
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);

        // relations
        $this->assertArrayHasKey('updated_by', $result);
        $this->assertArraySubset(
            (new User($item->updatedBy))->toArray($request),
            $result['updated_by']->toArray($request)
        );
    }
}
