<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\LocationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LocationCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $category = factory(LocationCategory::class)->create();

        $this->assertDatabaseHas('location_categories', [
            'id' => $category->id,
            'name' => $category->name
        ]);

        $location = factory(Location::class)->create([
            'category_id' => $category->id
        ]);
        $l = $category->locations->first();
        $this->assertEquals($location->id, $l->id);
    }
}
