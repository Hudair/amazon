<?php

namespace Incevio\Package\Wallet\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Incevio\Package\Wallet\Models\Transaction;

class Created extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;

    public $tries = 5;

    public $timeout = 20;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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
        ->from(get_sender_email(), get_sender_name())
        ->subject(trans('wallet::lang.mail.created_subject'))
        ->markdown(
            'wallet::mails.approve',
            [
                'url' => url('admin/dashboard'),
                'receiver' => $this->transaction->payable->getName(),
                'amount' => get_formated_currency($this->transaction->amount, 2)
            ]
        );
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
            'amount' => trans('wallet::lang.mail.created_amount', ['amount' => get_formated_currency($this->transaction->amount, 2)]),
        ];
    }
}
