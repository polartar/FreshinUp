<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\User;
use App\Models\Foodfleet\Document;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Testing\File;

class DocumentTest extends TestCase
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


        $documents = factory(Document::class, 5)->create([
            'created_by_uuid' => $user->uuid,
            'type' => 1,
            'status' => 1
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/documents")
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
                'status' => $document->status,
                'type' => $document->type,
                'description' => $document->description,
                'notes' => $document->notes,
                'created_at' => str_replace('"', '', json_encode($document->created_at)),
                'updated_at' => str_replace('"', '', json_encode($document->updated_at))
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

        factory(Document::class, 5)->create([
            'title' => 'Not visibles',
            'created_by_uuid' => $user->uuid,
            'type' => 1,
            'status' => 1
        ]);

        $documentsToFind = factory(Document::class, 5)->create([
            'title' => 'To find',
            'created_by_uuid' => $user->uuid,
            'type' => 1,
            'status' => 1
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/documents")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/documents?filter[title]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($documentsToFind as $idx => $document) {
            $this->assertArraySubset([
                'uuid' => $document->uuid,
                'title' => $document->title,
                'status' => $document->status,
                'type' => $document->type,
                'description' => $document->description,
                'notes' => $document->notes,
                'assigned' => null,
                'created_at' => str_replace('"', '', json_encode($document->created_at)),
                'updated_at' => str_replace('"', '', json_encode($document->updated_at))
            ], $data[$idx]);
        }
    }

    public function testEditFile()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $document = factory(Document::class)->create([
            'title' => 'To find',
            'created_by_uuid' => $user->uuid,
            'type' => 1,
            'status' => 1
        ]);

        Storage::fake('cms');
        $file = File::create('document.pdf', 100);

        $document
            ->addMediaFromUrl($file->getRealPath())
            ->usingFileName('document.pdf')
            ->toMediaCollection('attachment');
        $attachment = $document->getFirstMedia('attachment');

        $url = 'api/foodfleet/documents/' . $document->uuid;
        $returnedDocument = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('document.pdf', $returnedDocument['file']['name']);
        $this->assertEquals($attachment->getPath(), $returnedDocument['file']['src']);
    }
}
