<?php

namespace App\Notifications\Refund;

use App\Notifications\Push\HasNotifications;
use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Initiated extends Notification implements ShouldQueue
{
    use Queueable;

    public $refund;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->refund->order->device_id !== null) {
            HasNotifications::pushNotification(self::toArray($notifiable));
        }

        if ($this->refund->order->customer_id) {
            return ['mail', 'database'];
        }

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
            ->from(get_sender_email(), get_sender_name())
            ->subject(trans('notifications.refund_initiated.subject', ['order' => $this->refund->order->order_number]))
            ->markdown('admin.mail.refund.initiated', ['refund' => $this->refund]);
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
            'order' => $this->refund->order->order_number,
            'device_id' => $this->refund->order->device_id,
            'status' => $this->refund->statusName(),
            'amount' => $this->refund->amount,
            'subject' => trans('notifications.refund_initiated.subject', ['order' => $this->refund->order->order_number]),
            'message' => trans('notifications.refund_initiated.message', ['order' => $this->refund->order->order_number]),
        ];
    }
}
