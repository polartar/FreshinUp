<?php

namespace App\Actions;

use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Store;
use App\Enums\StoreAssigned as StoreAssignedEnum;

class UpdateStore implements Action
{
    public function execute(array $data)
    {
        $store = Store::where('uuid', $data['uuid'])
            ->first();

        $collection = collect($data);
        $updateData = $collection->except(['uuid'])->all();
        $store->update($updateData);

        return $store->refresh();
    }
}
