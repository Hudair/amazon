<?php

namespace App\Notifications\Message;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public $receiver;

    public $guest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message, $receiver, $guest = false)
    {
        $this->message = $message;
        $this->receiver = $receiver;
        $this->guest = $guest;
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
        return (new MailMessage)
            ->from(get_sender_email($this->message->shop_id), get_sender_name($this->message->shop_id))
            ->subject(trans('notifications.new_message.subject', [
                'subject' => $this->message->subject
            ]))
            ->markdown('admin.mail.message.new_message', [
                'url' => route('admin.support.message.show', $this->message->id),
                'message' => $this->message,
                'receiver' => $this->receiver,
                'guest' => $this->guest
            ]);
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
