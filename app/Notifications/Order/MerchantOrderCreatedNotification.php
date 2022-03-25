<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Notifications\Push\HasNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MerchantOrderCreatedNotification extends Notification implements ShouldQueue
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
        $subject = trans('notifications.merchant_order_created_notification.subject', [
            'order' => $this->order->order_number
        ]);

        return (new MailMessage)
            ->from(get_sender_email(), get_sender_name())
            ->subject($subject)
            ->markdown('admin.mail.order.merchant_order_created_notification', [
                'url' => route('admin.order.order.show', $this->order->id),
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
            'customer' => $this->order->customer->name,
            'subject' => trans('notifications.merchant_order_created_notification.subject', ['order' => $this->order->order_number]),
            'message' => trans('notifications.merchant_order_created_notification.message', ['order' => $this->order->order_number]),
        ];
    }
}
