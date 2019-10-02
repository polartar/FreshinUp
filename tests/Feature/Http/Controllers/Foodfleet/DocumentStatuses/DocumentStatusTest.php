<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\User;
use App\Models\Foodfleet\DocumentStatus;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentStatusTest extends TestCase
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

        $documentStatuses = factory(DocumentStatus::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/document-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($documentStatuses as $idx => $documentStatus) {
            $this->assertArraySubset([
                'value' => $documentStatus->id,
                'text' => $documentStatus->name,
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

        factory(DocumentStatus::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $documentStatusesToFind = factory(DocumentStatus::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/document-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/document-statuses?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($documentStatusesToFind as $idx => $documentStatus) {
            $this->assertArraySubset([
                'value' => $documentStatus->id,
                'text' => $documentStatus->name,
            ], $data[$idx]);
        }
    }
}
