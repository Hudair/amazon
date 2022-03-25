<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Models\Customer;
use App\Notifications\Push\HasNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->order->device_id !== null) {
            HasNotifications::pushNotification(self::toArray($notifiable));
        }

        if ($notifiable instanceof Customer) {
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
        $subject = trans('notifications.order_created.subject', ['order' => $this->order->order_number]);

        return (new MailMessage)
            ->from(get_sender_email(), get_sender_name())
            ->subject($subject)
            ->markdown('admin.mail.order.created', [
                'url' => url('/'),
                'order' => $this->order
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
            'order' => $this->order->order_number,
            'device_id' => $this->order->device_id,
            'subject' => trans('notifications.order_created.subject', ['order' => $this->order->order_number]),
            'message' => trans('notifications.order_created.message', ['order' => $this->order->order_number]),
            'status' => $this->order->orderStatus(true),
        ];
    }
}
