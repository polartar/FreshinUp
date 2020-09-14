<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Item;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
    public function testModel()
    {
        $item = factory(Item::class)->create();
        $company = factory(Company::class)->create();
        $supplier = \App\Models\Foodfleet\Company::find($company->id);

        $category = factory(Category::class)->create();
        $category->items()->save($item);
        $category->supplier()->associate($supplier);
        $category->save();

        $this->assertDatabaseHas('categories', [
            'uuid' => $category->uuid,
            'supplier_uuid' => $supplier->uuid
        ]);

        $this->assertDatabaseHas('items', [
            'uuid' => $item->uuid,
            'category_uuid' => $category->uuid
        ]);
    }
}
