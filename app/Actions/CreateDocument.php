<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Document;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;


class CreateDocument implements Action
{
    public function execute(array $data)
    {
        $collection = collect($data);
        $createData = $collection->except(['assigned_type', 'assigned_uuid'])->all();

        $document = Document::create($createData);

        if ($data['assigned_type'] && $data['assigned_uuid']) {
            $assignedModelName = DocumentAssignedEnum::getDescription($data['assigned_type']);
            $assigned = (new $assignedModelName)::where('uuid', $data['assigned_uuid'])->first();
            $assigned->documents()->save($document);
        }

        return $document;
    }
}
