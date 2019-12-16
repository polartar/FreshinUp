<?php

namespace App\Actions;

use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Actions\Action;
use App\Models\Foodfleet\Message;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageShipped;

class CreateMessage implements Action
{
    public function execute(array $data)
    {
        $user = User::where('uuid', $data['created_by_uuid'])->first();
        $manager = Event::with('manager')->where('uuid', $data['event_uuid'])->first()->manager;
        $supplier = Store::with('supplier')->where('uuid', $data['store_uuid'])->first()->supplier;

        $recipient;
        if ($user->id == $supplier->users_id) {
            $data['recipient_uuid'] = $manager->uuid;
            $recipient = $manager;
        } elseif (!empty($supplier->users_id) && $user->id == $manager->id) {
            $supplierOwner = User::where('id', $supplier->users_id)->first();
            $data['recipient_uuid'] = $supplierOwner->uuid;
            $recipient = $supplierOwner;
        }

        $collection = collect($data);
        $createData = $collection->all();
        $message = Message::create($createData);

        if (!empty($recipient)) {
            $this->sendMessage($message, $user, $recipient);
        }

        return $message->refresh();
    }

    private function sendMessage(Message $message, User $user, User $recipient)
    {
        if (!empty($user->email) && !empty($recipient->email)) {
            $name = $recipient->first_name . ' ' . $recipient->last_name;
            Mail::to($recipient->email)->send(new MessageShipped($user, $message));
        }
    }
}
