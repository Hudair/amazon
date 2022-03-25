<?php

namespace App\Notifications\Dispute;

use App\Models\Reply;
use App\Notifications\Push\HasNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Updated extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->reply->repliable->order->device_id !== null) {
            HasNotifications::pushNotification(self::toArray($notifiable));
        }

        return ['mail', 'database'];
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
            ->subject(trans('notifications.dispute_updated.subject', [
                'order_id' => $this->reply->repliable->order->order_number
            ]))
            ->markdown('admin.mail.dispute.updated', [
                'url' => route('admin.support.dispute.show', $this->reply->repliable_id),
                'reply' => $this->reply
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
            'id' => $this->reply->repliable_id,
            'device_id' => $this->reply->repliable->order->device_id,
            'status' => $this->reply->repliable->statusName(),
            'category' => $this->reply->repliable->dispute_type->detail,
            'order_number' => $this->reply->repliable->order->order_number,
            'subject' => trans('notifications.dispute_updated.subject', [
                'order_id' => $this->reply->repliable->order->order_number
            ]),
            'message' => trans('notifications.dispute_updated.message', [
                'order_id' => $this->reply->repliable->order->order_number
            ]),
        ];
    }
}
