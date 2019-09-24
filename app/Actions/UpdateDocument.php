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
        $document = Document::findOrFail($data['id']);

        $collection = collect($data);
        $updateData = $collection->except(['assigned_type', 'assigned_uuid', 'id'])->all();
        $document->update($updateData);

        if ($data['assigned_type'] && $data['assigned_uuid']) {
            $assignedModelName = DocumentAssignedEnum::getDescription($data['assigned_type']);
            $assigned = (new $assignedModelName)::where('uuid', $data['assigned_uuid'])->first();
            $assigned->documents()->save($document);
        }

        return $document;
    }
}
