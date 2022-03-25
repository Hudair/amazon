<?php

namespace App\Listeners\Message;

use App\Events\Message\MessageReplied;
use App\Notifications\Message\Replied as MessageRepliedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class NotifyAssociatedUsersMessagetReplied implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageReplied  $event
     * @return void
     */
    public function handle(MessageReplied $event)
    {
        if (! config('system_settings')) {
            setSystemConfig(optional($event->reply->repliable)->shop_id);
        }

        if ($event->reply->user_id) {
            if ($event->reply->repliable->customer->email) {
                $event->reply->repliable->customer->notify(
                    new MessageRepliedNotification($event->reply, $event->reply->repliable->customer->getName())
                );
            } elseif ($event->reply->repliable->email) {
                Notification::route('mail', $event->reply->repliable->email)
                    // ->route('nexmo', '5555555555')
                    ->notify(new MessageRepliedNotification($event->reply, $event->reply->repliable->name));
            }
        } elseif ($event->reply->customer_id) {
            if ($event->reply->repliable->user->email) {
                $event->reply->repliable->user->notify(
                    new MessageRepliedNotification($event->reply, $event->reply->repliable->user->getName())
                );
            } elseif (config('shop_settings.notify_new_message')) {
                $event->reply->repliable->shop->notify(
                    new MessageRepliedNotification($event->reply, $event->reply->repliable->shop->name)
                );
            }
        }
    }
}
