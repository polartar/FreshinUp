<?php

namespace App\Actions;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Message;
use Illuminate\Support\Facades\Storage;

class CreateMessage implements Action
{
    public function execute(array $data)
    {
        $collection = collect($data);
        $createData = $collection->all();

        $message = Message::create($createData);

        return $message->refresh();
    }
}
