<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\User;
use App\Models\Foodfleet\DocumentType;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTypeTest extends TestCase
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

        $documentTypes = factory(DocumentType::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/document-types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($documentTypes as $idx => $documentType) {
            $this->assertArraySubset([
                'value' => $documentType->id,
                'text' => $documentType->name,
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

        factory(DocumentType::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $documentTypesToFind = factory(DocumentType::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/document-types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/document-types?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($documentTypesToFind as $idx => $documentType) {
            $this->assertArraySubset([
                'value' => $documentType->id,
                'text' => $documentType->name,
            ], $data[$idx]);
        }
    }
}
