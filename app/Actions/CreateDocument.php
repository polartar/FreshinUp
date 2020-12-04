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
        $createData = $collection->except(['file'])->all();

        $assigned_type = Document::getAssignedModel($createData['assigned_type']);
        if ($assigned_type) {
            $createData['assigned_type'] = $assigned_type;
        } else {
            unset($createData['assigned_type']);
            unset($createData['assigned_uuid']);
        }
        // TODO: validate assigned
        $document = Document::create($createData);

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
