<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Suppliers\Documents;

use App\Models\Foodfleet\Document;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    public function testGetList()
    {
        $supplier = factory(User::class)->create([
            'type' => 1
        ]);

        Passport::actingAs($supplier);

        $documents = factory(Document::class, 5)->create(
            [
                'assigned_uuid' => $supplier->uuid
            ]
        );
        $url = "/api/foodfleet/supplier/" . $supplier->uuid . "/documents";
        $response = $this->json('GET', $url);
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($documents as $idx => $document) {
            $this->assertArraySubset([
                'uuid' => $document->uuid,
                'title' => $document->title,
                'assigned_uuid' => $document->assigned_uuid
            ], $data[$idx]);
        }
    }
}
