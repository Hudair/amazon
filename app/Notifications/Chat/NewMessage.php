<?php

namespace App\Notifications\Chat;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $text;
    public $repliable;
    public $recipent;
    public $sender;

    /**
     * Get the message.
     *
     * @param  message  $repliable
     * @return void
     */
    public function __construct($recipent, $sender, $text, $repliable)
    {
        $this->repliable = $repliable;
        $this->text = $text;
        $this->recipent = $recipent;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if (!config('shop_settings')) {
            setSystemConfig($this->repliable->shop_id);
        }

        if (config('shop_settings.notify_new_chat')) {
            return ['database', 'mail'];
        }

        return ['database'];
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
            ->from(get_sender_email(), get_sender_name())
            ->subject(trans('notifications.new_chat_message.subject', ['sender' => $this->sender]))
            ->markdown('admin.mail.chat.new_message', [
                'url' => route('admin.support.chat_conversation.show', $this->repliable->id),
                'receipent' => $this->recipent,
                'message' => $this->text
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'chat_id' => $this->repliable->id,
            'text' => $this->text,
        ];
    }
}
