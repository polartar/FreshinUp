<?php

namespace Tests\Unit\Models;

use App\Enums\DocumentStatus;
use App\Enums\StoreStatus as StoreStatusEnum;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $company = factory(Company::class)->create();
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $event = factory(Event::class)->create();
        $staff = factory(Staff::class)->create();

        $store = factory(Store::class)->create();
        $area = factory(StoreArea::class)->create([
            'store_uuid' => $store->uuid
        ]);
        $this->assertEquals($store->owner_uuid, $store->owner->uuid);
        $store->events()->save($event);
        $store->supplier()->associate($supplier);
        $store->save();
        $store->staffs()->sync($staff->uuid);

        $this->assertDatabaseHas('stores', [
            'uuid' => $store->uuid,
            'status_id' => $store->status_id,
            'address_uuid' => $store->address_uuid,
            'supplier_uuid' => $store->supplier_uuid,
            'owner_uuid' => $store->owner_uuid,
            'type_id' => $store->type_id,
            'square_id' => $store->square_id,
            'contact_phone' => $store->contact_phone,
            'size' => $store->size,
            'name' => $store->name,
            'state_of_incorporation' => $store->state_of_incorporation,
            'website' => $store->website,
            'twitter' => $store->twitter,
            'facebook' => $store->facebook,
            'instagram' => $store->instagram,
            'staff_notes' => $store->staff_notes,
        ]);

        $this->assertDatabaseHas('events_stores', [
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $this->assertDatabaseHas('stores_staffs', [
            'staff_uuid' => $staff->uuid,
            'store_uuid' => $store->uuid
        ]);

        $this->assertDatabaseHas('users', [
            'uuid' => $store->owner_uuid,
        ]);

        $this->assertDatabaseHas('companies', [
            'uuid' => $store->supplier_uuid,
        ]);

        $this->assertDatabaseHas('store_areas', [
            'id' => $area->id,
            'store_uuid' => $area->store_uuid,
        ]);

        $this->assertEquals($area->id, $store->areas->first()->id);
    }

    public function testObserverWhenItemCreated()
    {
        // None existing before
        $store = factory(Store::class)->make();
        $this->assertEquals(0, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid
        ])->count());

        // sample documents on new fleet member (store)
        $store->save();
        $documents = [
            'Copy of Business License',
            'Seller\'s Permit',
            'NDA',
            'Equipment List',
            'W-9',
            'Copy of Auto Insurance Card',
            'Copy of Health Permit',
            'Food Fleet Contract',
        ];
        foreach ($documents as $document) {
            $this->assertEquals(1, Document::where([
                'assigned_type' => Store::class,
                'assigned_uuid' => $store->uuid,
                'status_id' => DocumentStatus::PENDING,
                'title' => $document
            ])->count());
        }
    }

    public function testObserverWhenStoreUpdateWithStatusDifferentOfPending()
    {
        $store = factory(Store::class)->create([
            'status_id' => StoreStatusEnum::DRAFT
        ]);
        $storeDocumentSize = Document::where([ 'assigned_uuid' => $store->uuid ])->count();
        $this->assertEquals($storeDocumentSize, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $store->update([
            'status_id' => StoreStatusEnum::APPROVED
        ]);
        $this->assertEquals($storeDocumentSize, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());
        $document = Document::where([
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->first();
        $this->assertNotNull($document->uuid);
    }

    public function testObserverWhenStoreUpdatedWithStatusPending()
    {
        $store = factory(Store::class)->create([
            'status_id' => StoreStatusEnum::DRAFT
        ]);
        $storeDocumentSize = Document::where([ 'assigned_uuid' => $store->uuid ])->count();
        $this->assertEquals($storeDocumentSize, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $store->update([
            'status_id' => StoreStatusEnum::PENDING
        ]);
        $this->assertEquals($storeDocumentSize + 1, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $document = Document::where([
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->first();
        $this->assertNotNull($document->uuid);
    }

    public function testObserverWhenStoreUpdatedWithStatusPendingWhenHasChangedToPending()
    {
        $store = factory(Store::class)->create([
            'status_id' => StoreStatusEnum::DRAFT
        ]);
        $storeDocumentSize = Document::where([ 'assigned_uuid' => $store->uuid ])->count();
        $this->assertEquals($storeDocumentSize, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $store->update([
            'status_id' => StoreStatusEnum::PENDING
        ]);
        $this->assertEquals($storeDocumentSize + 1, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $store->update([
            'status_id' => StoreStatusEnum::APPROVED
        ]);
        $this->assertEquals($storeDocumentSize + 1, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $store->update([
            'status_id' => StoreStatusEnum::PENDING
        ]);
        $this->assertEquals($storeDocumentSize + 1, Document::where([
            'assigned_type' => Store::class,
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->count());

        $document = Document::where([
            'assigned_uuid' => $store->uuid,
            'status_id' => DocumentStatus::PENDING
        ])->first();
        $this->assertNotNull($document->uuid);
    }
}
