<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

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
            'type_id' => 1,
            'status_id' => 1
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
                'status_id' => $document->status_id,
                'type_id' => $document->type_id,
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
            'type_id' => DocumentType::FROM_TEMPLATE,
            'status_id' => DocumentStatus::PENDING
        ]);

        $documentsToFind = factory(Document::class, 5)->create([
            'title' => 'To find',
            'created_by_uuid' => $user->uuid,
            'type_id' => 1,
            'status_id' => 1
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
                'status_id' => $document->status_id,
                'type_id' => $document->type_id,
                'description' => $document->description,
                'notes' => $document->notes,
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
            'type_id' => 1,
            'status_id' => 1
        ]);

        Storage::fake('cms');
        $file = File::create('document.pdf', 100);

        $document
            ->addMediaFromUrl($file->getRealPath())
            ->usingFileName('document.pdf')
            ->toMediaCollection('attachment');
        $attachment = $document->getFirstMedia('attachment');

        $url = 'api/foodfleet/documents/'.$document->uuid;
        $returnedDocument = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('document.pdf', $returnedDocument['file']['name']);
        $this->assertEquals($attachment->getPath(), $returnedDocument['file']['src']);
    }

    public function testGetDocumentsByAssignedUUIDAndEventStoreUUID()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();
        $anotherStore = factory(Store::class)->create();

        $event = factory(Event::class)->create();
        $anotherEvent = factory(Event::class)->create();

        $eventStoreUUID = $store->uuid;
        $anotherEventStoreUUID = $anotherStore->uuid;

        $document = factory(Document::class)->create([
            'assigned_uuid' => $event->uuid,
            'event_store_uuid' => $eventStoreUUID,
            'assigned_type' => 'App\Models\Foodfleet\Event'
        ]);
        $anotherDocument = factory(Document::class)->create([
            'assigned_uuid' => $anotherEvent->uuid,
            'event_store_uuid' => $anotherEventStoreUUID,
            'assigned_type' => 'App\Models\Foodfleet\Event'
        ]);

        $response = $this->get('/api/foodfleet/documents?'
            .'filter[assigned_uuid]='.$event->uuid
            .'&filter[event_store_uuid]='.$eventStoreUUID);

        $this->assertEquals(1, count($response->json('data')));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'event_store_uuid' => $eventStoreUUID,
                        'assigned_uuid' =>  $event->uuid
                    ]
                ]
            ]);
    }

    // TODO: test document creation or at least test actions/CreateDocument
}
