<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Categories;

use App\Models\Foodfleet\Square\Category;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $categories = factory(Category::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/categories")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($categories as $idx => $category) {
            $this->assertArraySubset([
                'uuid' => $category->uuid,
                'name' => $category->name
            ], $data[$idx]);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Category::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $categoriesToFind = factory(Category::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/categories")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/categories?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($categoriesToFind as $idx => $category) {
            $this->assertArraySubset([
                'uuid' => $category->uuid,
                'name' => $category->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('get', "/api/foodfleet/categories?filter[uuid]=" . $categoriesToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $categoriesToFind->first()->uuid,
            'name' => $categoriesToFind->first()->name
        ], $data[0]);
    }
}
