<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Store\Store as StoreResource;
use App\Models\Foodfleet\Store as StoreModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class StoreTest extends TestCase {

    public function testResource () {
        $store = factory(StoreModel::class)->create();
        $resource = new StoreResource($store);
        $expected = [
            'id' => $store->id,
            'uuid' => $store->uuid,
            'name' => $store->name,
            'square_id' => $store->square_id,
            'status_id' => $store->status_id,
            'type_id' => $store->type_id,
            'supplier_uuid' => $store->supplier_uuid,
            'website' => $store->website,
            'contact_phone' => $store->contact_phone,
            'size' => $store->size,
            'image' => $store->image,
            'owner_uuid' => $store->owner_uuid,
            'pos_system' => $store->pos_system,
            'state_of_incorporation' => $store->state_of_incorporation,
            'facebook' => $store->facebook,
            'twitter' => $store->twitter,
            'instagram' => $store->instagram,
            'staff_notes' => $store->staff_notes,
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
