<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Document;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;

class UpdateDocument implements Action
{
    public function execute(array $data)
    {
        $document = Document::where('uuid', $data['uuid'])
            ->with('assigned')
            ->with('owner')
            ->first();

        $collection = collect($data);
        $updateData = $collection->except(['assigned_type', 'assigned_uuid', 'uuid'])->all();
        $document->update($updateData);

        if ($collection->get('assigned_type') && $collection->get('assigned_uuid')) {
            $assignedModelName = DocumentAssignedEnum::getDescription($data['assigned_type']);
            $assigned = call_user_func(array($assignedModelName, 'where'), 'uuid', $data['assigned_uuid'])->first();
            $assigned->documents()->save($document);
        }

        return $document->refresh();
    }
}
