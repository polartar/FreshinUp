<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Document;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;
use Illuminate\Support\Facades\Storage;

class CreateDocument implements Action
{
    public function execute(array $data)
    {
        $collection = collect($data);
        $createData = $collection->except(['assigned_type', 'assigned_uuid', 'file'])->all();

        $document = Document::create($createData);

        if ($collection->get('assigned_type') && $collection->get('assigned_uuid')) {
            $assignedModelName = DocumentAssignedEnum::getDescription($data['assigned_type']);
            $assigned =  call_user_func(array($assignedModelName, 'where'), 'uuid', $data['assigned_uuid'])->first();
            $assigned->documents()->save($document);
        }

        if ($collection->get('file')) {
            $fileName = $data['file']['name'];
            $fileSrc = $data['file']['src'];
            if (Storage::disk('tmp')->exists($fileSrc)) {
                $url = Storage::disk('tmp')->temporaryUrl($fileSrc, now()->addMinutes(1));
                $document->addMediaFromUrl($url)
                    ->usingFileName($fileName)
                    ->toMediaCollection('attachment');
                Storage::disk('tmp')->delete($fileSrc);
            }
        }

        return $document->refresh();
    }
}
