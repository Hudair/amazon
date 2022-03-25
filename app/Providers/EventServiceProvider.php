<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Blog Events
        // 'App\Events\Blog\BlogPublished' => [
        //     'App\Listeners\Blog\EmailToSubscribers',
        // ],
        // 'App\Events\Blog\UserRepliedToConversation' => [
        //     'App\Listeners\Blog\EmailConversationSubscribers',
        // ],

        // Email senting events
        'Illuminate\Mail\Events\MessageSending' => [
            \App\Listeners\Mail\LogSendingMessage::class,
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            \App\Listeners\Mail\LogSentMessage::class,
        ],

        // Announcement Events
        \App\Events\Announcement\AnnouncementCreated::class => [
            \App\Listeners\Announcement\SendAnnouncementCreatedNotification::class,
        ],

        // Chat Events
        \App\Events\Chat\NewMessageEvent::class => [
            \App\Listeners\Chat\NotifyAssociatedUsers::class,
        ],

        // Customer Events
        \App\Events\Customer\Registered::class => [
            \App\Listeners\Customer\SendWelcomeEmail::class,
            \App\Listeners\Customer\RegisterNewsletter::class,
        ],
        \App\Events\Customer\CustomerCreated::class => [
            \App\Listeners\Customer\SendLoginInfo::class,
        ],
        \App\Events\Customer\CustomerProfileUpdated::class => [
            \App\Listeners\Customer\SendProfileUpdateNotification::class,
        ],
        \App\Events\Customer\PasswordUpdated::class => [
            \App\Listeners\Customer\NotifyCustomerPasswordUpdated::class,
        ],

        // Dispute Events
        \App\Events\Dispute\DisputeCreated::class => [
            \App\Listeners\Dispute\SendAcknowledgementNotification::class,
            \App\Listeners\Dispute\NotifyMerchantDisputeCreated::class,
        ],
        \App\Events\Dispute\DisputeUpdated::class => [
            \App\Listeners\Dispute\NotifyCustomerDisputeUpdated::class,
        ],
        \App\Events\Dispute\DisputeSolved::class => [
            \App\Listeners\Dispute\NotifyCustomerDisputeSolved::class,
        ],

        // Inventory Events
        // Neet to complete
        \App\Events\Inventory\InventoryLow::class => [
            \App\Listeners\Inventory\NotifyMerchantInventoryLow::class,
        ],
        // Neet to complete
        \App\Events\Inventory\StockOut::class => [
            \App\Listeners\Inventory\NotifyMerchantStockOut::class,
        ],

        // Message Events
        \App\Events\Message\NewMessage::class => [
            \App\Listeners\Message\SendNewMessageNotificationToReceiver::class,
        ],
        \App\Events\Message\MessageReplied::class => [
            \App\Listeners\Message\NotifyAssociatedUsersMessagetReplied::class,
        ],

        // Order Events
        \App\Events\Order\OrderCreated::class => [
            \App\Listeners\Order\NotifyCustomerOrderPlaced::class,
            \App\Listeners\Order\NotifyMerchantNewOrderPlaced::class,
            \App\Listeners\Order\LowInventoryCheck::class,
        ],
        \App\Events\Order\OrderUpdated::class => [
            \App\Listeners\Order\NotifyCustomerOrderUpdated::class,
        ],
        \App\Events\Order\OrderFulfilled::class => [
            \App\Listeners\Order\OrderBeenFulfilled::class,
        ],
        \App\Events\Order\OrderPaid::class => [
            \App\Listeners\Order\OrderBeenPaid::class,
        ],
        \App\Events\Order\OrderPaymentFailed::class => [
            \App\Listeners\Order\NotifyCustomerPaymentFailed::class,
        ],
        \App\Events\Order\OrderCancellationRequestCreated::class => [
            \App\Listeners\Order\NotifyMerchantNewOrderCancellationRequest::class,
            \App\Listeners\Order\NotifyCustomerOrderCancellationRequest::class,
        ],
        \App\Events\Order\OrderCancellationRequestApproved::class => [
            \App\Listeners\Order\NotifyCustomerOrderCancellationApproved::class,
        ],
        \App\Events\Order\OrderCancellationRequestDeclined::class => [
            \App\Listeners\Order\NotifyCustomerOrderCancellationDeclined::class,
        ],
        \App\Events\Order\OrderCancelled::class => [
            \App\Listeners\Order\NotifyCustomerOrderCancelled::class,
        ],

        // Profile Events
        \App\Events\Profile\ProfileUpdated::class => [
            \App\Listeners\Profile\NotifyUserProfileUpdated::class,
        ],
        \App\Events\Profile\PasswordUpdated::class => [
            \App\Listeners\Profile\NotifyUserPasswordUpdated::class,
        ],

        // Refund Events
        \App\Events\Refund\RefundInitiated::class => [
            \App\Listeners\Refund\NotifyCustomerRefundInitiated::class,
        ],
        \App\Events\Refund\RefundApproved::class => [
            \App\Listeners\Refund\NotifyCustomerRefundApproved::class,
        ],
        \App\Events\Refund\RefundDeclined::class => [
            \App\Listeners\Refund\NotifyCustomerRefundDeclined::class,
        ],

        // Shop Events
        \App\Events\Shop\ShopCreated::class => [
            \App\Listeners\Shop\NotifyMerchantShopCreated::class,
        ],
        \App\Events\Shop\ShopUpdated::class => [
            \App\Listeners\Shop\NotifyMerchantShopUpdated::class,
        ],
        \App\Events\Shop\ConfigUpdated::class => [
            \App\Listeners\Shop\NotifyMerchantConfigUpdated::class,
        ],
        \App\Events\Shop\ShopDeleted::class => [
            \App\Listeners\Shop\NotifyMerchantShopDeleted::class,
        ],
        \App\Events\Shop\DownForMaintainace::class => [
            \App\Listeners\Shop\NotifyMerchantShopDownForMaintainace::class,
        ],
        \App\Events\Shop\ShopIsLive::class => [
            \App\Listeners\Shop\NotifyMerchantShopIsLive::class,
        ],

        // Subscription Events
        \App\Events\Subscription\UserSubscribed::class => [
            \App\Listeners\Subscription\UpdateActiveSubscription::class,
            \App\Listeners\Subscription\UpdateTrialEndingDate::class,
        ],
        \App\Events\Subscription\SubscriptionUpdated::class => [
            \App\Listeners\Subscription\UpdateActiveSubscription::class,
        ],
        \App\Events\Subscription\SubscriptionCancelled::class => [
            \App\Listeners\Subscription\UpdateActiveSubscription::class,
        ],

        // System Events
        \App\Events\System\SystemInfoUpdated::class => [
            \App\Listeners\System\NotifyAdminSystemUpdated::class,
        ],
        \App\Events\System\SystemConfigUpdated::class => [
            \App\Listeners\System\NotifyAdminConfigUpdated::class,
        ],
        \App\Events\System\DownForMaintainace::class => [
            \App\Listeners\System\NotifyAdminSystemIsDown::class,
        ],
        \App\Events\System\SystemIsLive::class => [
            \App\Listeners\System\NotifyAdminSystemIsLive::class,
        ],

        // Ticket Events
        \App\Events\Ticket\TicketCreated::class => [
            \App\Listeners\Ticket\SendAcknowledgementNotification::class,
        ],
        \App\Events\Ticket\TicketAssigned::class => [
            \App\Listeners\Ticket\NotifyUserTicketAssigned::class,
        ],
        \App\Events\Ticket\TicketReplied::class => [
            \App\Listeners\Ticket\NotifyAssociatedUsersTicketReplied::class,
        ],
        \App\Events\Ticket\TicketUpdated::class => [
            \App\Listeners\Ticket\NotifyUserTicketUpdated::class,
        ],

        // User Events
        'Illuminate\Auth\Events\Registered' => [
            \App\Listeners\User\SendVerificationEmail::class,
            // 'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],
        \App\Events\User\UserCreated::class => [
            \App\Listeners\User\SendLoginInfo::class,
        ],
        \App\Events\User\UserUpdated::class => [
            \App\Listeners\User\NotifyUserProfileUpdated::class,
        ],
        'Illuminate\Auth\Events\PasswordReset' => [
            \App\Listeners\User\NotifyUserPasswordUpdated::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //     \SocialiteProviders\Apple\AppleExtendSocialite::class . '@handle',
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Queue::failing(function (JobFailed $event) {
            Log::channel('joblog')->error('Job Failed!', [
                'Queue Connection' => $event->connectionName,
                'Exception' => $event->exception,
            ]);
        });

        // Log all job precess except the UpdateVisitorTable
        Queue::before(function (JobProcessing $event) {
            if ($event->job->resolveName() != "App\Jobs\UpdateVisitorTable") {
                Log::channel('joblog')->info('............. Job Processing:: ' . $event->job->resolveName() . ' .................');
                Log::channel('joblog')->info(['payload' => $event->job->payload()]);
            }
        });

        Queue::after(function (JobProcessed $event) {
            if ($event->job->resolveName() != "App\Jobs\UpdateVisitorTable") {
                Log::channel('joblog')->info('......................... Job Processed Successfully .............................');
            }
        });
    }
}
