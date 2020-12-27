<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends ResetPassword
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Reset Password Notification'))
            ->line(Lang::getFromJson('You are receiving this email'
                . ' because we received a password reset request for your account.'))
            ->action(
                Lang::getFromJson('Reset Password'),
                url('/auth/reset-password/' . $this->token) . '?'
                . http_build_query(['email' => $notifiable->getEmailForPasswordReset()])
            )
            ->line(Lang::getFromJson(
                'This password reset link '
                . 'will expire in :count minutes.',
                [
                    'count' => config('auth.passwords.'
                    . config('auth.defaults.passwords') . '.expire')
                ]
            ))
            ->line(Lang::getFromJson('If you did not request a password reset,'
                . ' no further action is required.'));
    }
}
