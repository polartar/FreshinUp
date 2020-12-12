<?php

namespace App\Observers;

use App\Enums\DocumentStatus;
use App\Enums\StoreStatus as StoreStatusEnum;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Store;

class StoreObserver
{
    /**
     * Handle the event "created" event.
     *
     * @param  Store  $store
     * @return void
     */
    public function created(Store $store)
    {
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
            Document::create([
                'assigned_type' => Store::class,
                'assigned_uuid' => $store->uuid,
                'status_id' => DocumentStatus::PENDING,
                'title' => $document,
                'description' => $document,
            ]);
        }
    }

    public function updated(Store $store)
    {
        if ($store->isDirty('status_id')
            && $store->status_id == StoreStatusEnum::PENDING) {
            Document::updateOrCreate([
                'assigned_type' => Store::class,
                'assigned_uuid' => $store->uuid,
                'status_id' => DocumentStatus::PENDING,
                'title' => "Fleet Member event agreement",
                'description' => "Fleet Member event agreement",
            ]);
        }
    }
}
