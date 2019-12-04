<?php

namespace App\Mail;

use App\User;
use App\Models\Foodfleet\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageShipped extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = $this->user->email;
        $name = $this->user->first_name . ' ' . $this->user->last_name;
        
        return $this->from($from, $name)
                    ->subject('Event Activity Message')
                    ->markdown('emails.messages.shipped')
                    ->with([
                        'content' => $this->message->content,
                        'userName' => $name
                    ]);
    }
}
