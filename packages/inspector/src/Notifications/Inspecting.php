<?php

namespace Incevio\Package\Inspector\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Incevio\Package\Inspector\Services\InspectorService;

class Inspecting extends Notification implements ShouldQueue
{
    use Queueable;

    public $inspector;

    public $inspecting;

    public $keywords;

    public $caught;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InspectorService $inspector)
    {
        $this->inspector = $inspector;
        $this->keywords = get_from_option_table('inspector_keywords', []);
        $this->inspecting = Str::singular($inspector->model->getTable());
        $this->caught = (!empty($inspector->caught) ? implode(',', $inspector->caught) : Null);
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
        ->subject(trans('inspector::lang.inspecting_subject'))
        ->markdown(
            'inspector::mails.inspecting',
            [
                'url' => url('admin/dashboard'),
                'receiver' => $this->inspector->model->shop->getName(),
                'inspecting' => $this->inspecting,
                'caught' => $this->caught,
                'keywords' => implode(', ', $this->keywords),
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
            'inspecting' => trans('inspector::lang.inspecting_content', ['inspecting' => $this->inspecting]),
            'caught' => $this->caught,
        ];
    }
}
