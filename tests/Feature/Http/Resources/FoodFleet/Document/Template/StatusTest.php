<?php

namespace Tests\Feature\Http\Resources\Foodfleet\Document\Template;

use App\Enums\DocumentTemplateStatus as Enum;
use App\Http\Resources\Foodfleet\Document\Template\Status as Resource;
use App\Models\Foodfleet\Document\Template\Status as Model;
use Illuminate\Http\Request;
use Tests\TestCase;

class StatusTest extends TestCase {
    public function getDataProvider()
    {
        return [
            [ Enum::DRAFT, 'grey' ],
            [ Enum::PUBLISHED, 'success' ],
        ];
    }

    /** @dataProvider getDataProvider
     * @param $statusId
     * @param $color
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testResource ($statusId, $color) {
        $status = factory(Model::class)->make([
            'id' => $statusId
        ]);
        $resource = new Resource($status);
        $expected = [
          'id' => $status->id,
          'name' => $status->name,
          'color' => $color,
        ];
        $request = app()->make(Request::class);
        $this->assertArraySubset($expected, $resource->toArray($request));
    }

    public function getDescriptionProvider () {
        return [
            [Enum::DRAFT, 'means it\'s being edited, but not yet made available to be used as a doc template'],
            [Enum::PUBLISHED, 'released / made available to be used as a template']
        ];
    }

    /**
     * @dataProvider getDescriptionProvider
     * @param $statusId
     * @param $description
     */
    public function testDescription ($statusId, $description) {
        $this->assertEquals(Resource::getDescriptionFor($statusId), $description);
    }
}
