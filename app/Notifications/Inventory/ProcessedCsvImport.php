<?php

namespace App\Notifications\Inventory;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProcessedCsvImport extends Notification implements ShouldQueue
{
    use Queueable;

    private $failed_list = [];

    private $success_counter;

    private $failed_counter;

    private $failed_file_path;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($failed_list, $success_counter, $failed_file_path = '')
    {
        $this->failed_list = $failed_list;
        $this->success_counter = $success_counter;
        $this->failed_counter = count($failed_list);
        $this->failed_file_path = $failed_file_path;
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
        $mail = new MailMessage;
        $mail->from(get_sender_email(), get_sender_name());
        $mail->subject(trans('notifications.inventory_bulk_upload_procceed_notice.subject'))
            ->markdown(
                'admin.mail.inventory.bulk_upload_procceed_notice',
                [
                    'url' => route('admin.stock.inventory.index'),
                    'success' => $this->success_counter,
                    'failed' => $this->failed_counter,
                    'failed_list' => $this->failed_list,
                ]
            );

        if ($this->failed_counter > 0 && $this->failed_file_path != '') {
            $mail->attach($this->failed_file_path);
        }

        return $mail;
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
