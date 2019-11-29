<?php

namespace App\Actions;

use App\User;
use App\Models\Foodfleet\Event;
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
        $collection = collect($data);
        $createData = $collection->all();

        $message = Message::create($createData);
        
        // need to mailtrap account config info
        // $this->sendMessage($message);
        
        return $message->refresh();
    }

    private function sendMessage(Message $message)
    {
        $manager = Event::with('manager')->where('uuid', $message->event_uuid)->first()->manager;
        $user = User::where('uuid', $message->created_by_uuid)->first();

        if (!empty($user->email) && !empty($manager->email)) {
            $name = $manager->first_name . ' ' . $manager->last_name;

            Mail::to($manager->email, $name)
                    ->send(new MessageShipped($user, $message));
        }
    }
}
