<?php

namespace Incevio\Package\Inspector\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Incevio\Package\Inspector\Services\InspectorService;

class DenyInspected extends Notification implements ShouldQueue
{
    use Queueable;

    public $inspector;

    public $deny;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InspectorService $inspector)
    {
        $this->inspector = $inspector;
        $this->deny = Str::singular($inspector->model->getTable());
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // if($notifiable instanceof Customer) {
            return ['mail', 'database'];
        // }

        // return ['mail'];
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
        ->subject(trans('inspector::lang.deny_subject'))
        ->markdown(
            'inspector::mails.deny_inspected',
            [
                'url' => url('admin/dashboard'),
                'receiver' => $this->inspector->model->shop->getName(),
                'deny' => $this->deny,
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
            'deny' => trans('inspector::lang.deny_content', ['deny' => $this->deny]),
        ];
    }
}
