<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $url = null)
    {
        $this->token = $token;
        $this->url = $url;
    }

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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->url ?? url(config('app.url') . route('customer.password.reset', $this->token, false));

        return (new MailMessage)
            ->from(get_sender_email(), get_sender_name())
            ->subject(trans('notifications.customer_password_reset.subject'))
            ->markdown('admin.mail.auth.customer_password_reset', ['url' => $url]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
