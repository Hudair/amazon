<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Email Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the Notification library to build
    | the Notification emails. You are free to change them to anything
    | you want to customize your views to better match your platform.
    | Supported colors are blue, green, and red.
    |
    */

    // Auth Notifications
    'password_updated' => [
        'subject' => 'Your :marketplace password has been updated successfully!',
        'greeting' => 'Hello :user!',
        'message' => 'Your account login password has been changed successfully! If you did not make this change, please contact us asap! Click the button below to login into your profile page.',
        'button_text' => 'Visit Your Profile',
    ],

    // Billing Notifications
    'invoice_created' => [
        'subject' => ':marketplace Monthly subscription fee invoice',
        'greeting' => 'Hello :merchant!',
        'message' => 'Thanks for your continued support. We\'ve attached a copy of your invoice for your records. Please let us know if you have any questions or concerns!',
        'button_text' => 'Go to the Dashboard',
    ],

    // Customer Notifications
    'customer_registered' => [
        'subject' => 'Welcome to the :marketplace marketplace!',
        'greeting' => 'Congratulation :customer!',
        'message' => 'Your account has been created successfully! Click the button below to verify your email address.',
        'button_text' => 'Verify me',
    ],

    'customer_updated' => [
        'subject' => 'Account information updated successfully!',
        'greeting' => 'Hello :customer!',
        'message' => 'This is a notification to let you know that your account has been updated successfully!',
        'button_text' => 'Visit your profile',
    ],

    'customer_password_reset' => [
        'subject' => 'Reset Password Notification',
        'greeting' => 'Hello!',
        'message' => 'You are receiving this email because we received a password reset request for your account. If you did not request a password reset, Just ignore this notification and no further button_text is required.',
        'button_text' => 'Reset Password',
    ],

    // Dispute Notifications
    'dispute_acknowledgement' => [
        'subject' => '[Order ID: :order_id] Dispute has been submitted successfully',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that we have received your dispute  for the Order ID: :order_id, Our support team will get back to you as soon as possible.',
        'button_text' => 'View the dispute',
    ],

    'dispute_created' => [
        'subject' => 'New dispute for Order ID: :order_id',
        'greeting' => 'Hello :merchant!',
        'message' => 'You have received a new dispute for Order ID: :order_id. Please review and resolve the issue with the customer.',
        'button_text' => 'View the dispute',
    ],

    'dispute_updated' => [
        'subject' => '[Order ID: :order_id] Dispute status has been updated!',
        'greeting' => 'Hello :customer!',
        'message' => 'A dispute for the Order ID :order_id has been updated with this message from the vendor ":reply". Please check below and contact us if you need any assistance.',
        'button_text' => 'View the dispute',
    ],

    'dispute_appealed' => [
        'subject' => '[Order ID: :order_id] Dispute appealed!',
        'greeting' => 'Hello!',
        'message' => 'A dispute for the Order ID :order_id has been appealed with this message ":reply". Please check below for detail.',
        'button_text' => 'View the dispute',
    ],

    'appealed_dispute_replied' => [
        'subject' => '[Order ID: :order_id] New respons for appealed dispute!',
        'greeting' => 'Hello!',
        'message' => 'A dispute for the Order ID :order_id has been responded with this message: </br></br> ":reply"',
        'button_text' => 'View the dispute',
    ],

    // Inventory
    'low_inventory_notification' => [
        'subject' => 'Low inventory alert!',
        'greeting' => 'Hello!',
        'message' => 'One or more of your inventory items getting low. It time to add more inventory to keep the item live on the marketplace.',
        'button_text' => 'Update Inventory',
    ],

    'inventory_bulk_upload_procceed_notice' => [
        'subject' => 'Your bulk inventory import request has been procceed.',
        'greeting' => 'Hello!',
        'message' => 'We\'re happy to let you know that your bulk inventory import request has been procceed. Total number of rows imported successfully into the platform :success, Failed number of rows :failed ',
        'failed' => 'Please find the attached file for failed rows.',
        'button_text' => 'View Inventory',
    ],

    // Message Notifications
    'new_message' => [
        'subject' => ':subject',
        'greeting' => 'Hello :receiver',
        'message' => ':message',
        'button_text' => 'View the message on site',
    ],

    'message_replied' => [
        'subject' => ':user replied :subject',
        'greeting' => 'Hello :receiver',
        'message' => ':reply',
        'button_text' => 'View the message on site',
    ],

    // Order Notifications
    'order_created' => [
        'subject' => '[Order ID: :order] your order has been placed successfully!',
        'greeting' => 'Hello :customer',
        'message' => 'Thank you for choosing us! Your order [Order ID :order] has been placed successfully. We\'ll let you know the status of the order.',
        'button_text' => 'Visit the shop',
    ],

    'merchant_order_created_notification' => [
        'subject' => 'New order [Order ID: :order] has been placed on your shop!',
        'greeting' => 'Hello :merchant',
        'message' => 'A new order [Order ID :order] has been placed. Please check the order detail and fulfill the order asap.',
        'button_text' => 'Fulfill the order',
    ],

    'order_updated' => [
        'subject' => '[Order ID: :order] your order status has been updated!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that your order [Order ID :order] has been updated. Please see below for order detail. You can also check your orders from your dashboard.',
        'button_text' => 'Visit the shop',
    ],

    'order_fulfilled' => [
        'subject' => '[Order ID: :order] Your order on it\'s way!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that your order [Order ID :order] has been shipped and it\'s on your way. Please see below for order detail. You can also check your orders from your dashboard.',
        'button_text' => 'Visit the shop',
    ],

    'order_paid' => [
        'subject' => '[Order ID: :order] Your order been paid successfully!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that your order [Order ID :order] has been paid successfully and it\'s on your way. Please see below for order detail. You can also check your orders from your dashboard.',
        'button_text' => 'Visit the shop',
    ],

    'order_payment_failed' => [
        'subject' => '[Order ID: :order] payment failed!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that your order [Order ID :order] payment has been failed. Please see below for order detail. You can also check your orders from your dashboard.',
        'button_text' => 'Visit the shop',
    ],

    'cancellation_request_acknowledgement' => [
        'subject' => '[Order ID: :order] your cancellation request has been placed successfully!',
        'greeting' => 'Hello :customer',
        'message' => 'Thank you for choosing us! Your order [Order ID :order] cancellation request has been placed successfully. We\'ll let you know the status of the order.',
        'button_text' => 'Visit the shop',
    ],

    'merchant_order_cancellation_notification' => [
        'subject' => 'New order cancellation request has been created on your shop [Order ID: :order] .',
        'greeting' => 'Hello :merchant',
        'message' => 'A order [Order ID :order] cancellation request has been created. Please check the order detail and respond the request asap.',
        'button_text' => 'Respond the request',
    ],

    'cancellation_request_approved' => [
        'subject' => 'Your [Order ID: :order] item(s) cancellation request has been approved!',
        'greeting' => 'Hello :customer',
        'message' => 'Your [Order ID :order] item(s) cancellation request has been approved by the vendor. Thank you for choosing us!',
        'button_text' => 'Visit the shop',
    ],

    'cancellation_request_declined' => [
        'subject' => 'Too late to cancel your [Order ID: :order]',
        'greeting' => 'Hello :customer',
        'message' => 'We\'re really sorry! It\'s too late to accept your [Order ID :order] item(s) cancellation request by the vendor. If you don\'t like to have the item(s) you can still return.',
        'button_text' => 'Visit the shop',
    ],

    'order_canceled' => [
        'subject' => '[Order ID: :order] your order has been canceled!',
        'greeting' => 'Hello :customer',
        'message' => 'Your order [Order ID :order] has been canceled. Thank you for choosing us!',
        'button_text' => 'Visit the shop',
    ],

    // Refund Notifications
    'refund_initiated' => [
        'subject' => '[Order ID: :order] a refund has been initiated!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that we have initiated a refund request for your order :order. One of our team memeber will review the request soon. We\'ll let you know the status of the request.',
    ],

    'refund_approved' => [
        'subject' => '[Order ID: :order] a refund request has been approved!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that we have approved a refund request for your order :order. Refunded amount is :amount. We have sent the money to your payment method, it may take few days to effect your account. Contact your payment provider if you don\'t see the money effected in few days.',
    ],

    'refund_declined' => [
        'subject' => '[Order ID: :order] a refund request has been declined!',
        'greeting' => 'Hello :customer',
        'message' => 'This is a notification to let you know that a refund request has been declined for your order :order. If you\'re not satisfied with the merchant\'s solution, you can contact to the merchant directly from the platform or even you can appeal the dispute on :marketplace. We\'ll step in to solve the issue.',
    ],

    // Shop Notifications
    'shop_created' => [
        'subject' => 'Your shop is ready to go!',
        'greeting' => 'Congratulation :merchant!',
        'message' => 'Your shop :shop_name has been created successfully! Click the button below to login into shop admin panel.',
        'button_text' => 'Go to the Dashboard',
    ],

    'shop_updated' => [
        'subject' => 'Shop information updated successfully!',
        'greeting' => 'Hello :merchant!',
        'message' => 'This is a notification to let you know that your shop :shop_name has been updated successfully!',
        'button_text' => 'Go to the Dashboard',
    ],

    'shop_config_updated' => [
        'subject' => 'Shop configuration updated successfully!',
        'greeting' => 'Hello :merchant!',
        'message' => 'Your shop configuration has been updated successfully! Click the button below to login into shop admin panel.',
        'button_text' => 'Go to the Dashboard',
    ],

    'shop_down_for_maintainace' => [
        'subject' => 'Your shop is down!',
        'greeting' => 'Hello :merchant!',
        'message' => 'This is a notification to let you know that your shop :shop_name is down! No customer can visit your shop until it\'s back to live again.',
        'button_text' => 'Go to the Config page',
    ],

    'shop_is_live' => [
        'subject' => 'Your shop is back to LIVE!',
        'greeting' => 'Hello :merchant',
        'message' => 'This is a notification to let you know that your shop :shop_name is back to live successfully!',
        'button_text' => 'Go to the Dashboard',
    ],

    'shop_deleted' => [
        'subject' => 'Your shop has been removed from :marketplace!',
        'greeting' => 'Hello Merchant!',
        'message' => 'This is a notification to let you know that your shop has been deleted from our markerplace! We\'ll miss you.',
    ],

    // System Notifications
    'system_is_down' => [
        'subject' => 'Your marketplace :marketplace is down now!',
        'greeting' => 'Hello :user!',
        'message' => 'This is a notification to let you know that your marketplace :marketplace is down! No customer can visit your marketplace until it\'s back to live again.',
        'button_text' => 'Go to the config page',
    ],

    'system_is_live' => [
        'subject' => 'Your marketplace :marketplace is back to LIVE!',
        'greeting' => 'Hello :user!',
        'message' => 'This is a notification to let you know that your marketplace :marketplace is back to live successfully!',
        'button_text' => 'Go to the Dashboard',
    ],

    'system_info_updated' => [
        'subject' => ':marketplace - marketplace information updated successfully!',
        'greeting' => 'Hello :user!',
        'message' => 'This is a notification to let you know that your marketplace :marketplace has been updated successfully!',
        'button_text' => 'Go to the Dashboard',
    ],

    'system_config_updated' => [
        'subject' => ':marketplace - marketplace configuration updated successfully!',
        'greeting' => 'Hello :user!',
        'message' => 'The configuration of your marketplace :marketplace has been updated successfully! Click the button below to login into the admin panel.',
        'button_text' => 'View settings',
    ],

    'new_contact_us_message' => [
        'subject' => 'New message via contact us form: :subject',
        'greeting' => 'Hello!',
        'message_footer_with_phone' => 'You can reply this email or contact directly to this phone :phone',
        'message_footer' => 'You can reply this email directly.',
    ],

    // Ticket Notifications
    'ticket_acknowledgement' => [
        'subject' => '[Ticket ID: :ticket_id] :subject',
        'greeting' => 'Hello :user',
        'message' => 'This is a notification to let you know that we have received your ticket :ticket_id successfully! Our support team will get back to you as soon as possible.',
        'button_text' => 'View the ticket',
    ],

    'ticket_created' => [
        'subject' => 'New Support Ticket [Ticket ID: :ticket_id] :subject',
        'greeting' => 'Hello!',
        'message' => 'You have received a new support ticket ID :ticket_id, Sender :sender from the vendor :vendor. Review and assing the ticket to support team.',
        'button_text' => 'View the ticket',
    ],

    'ticket_assigned' => [
        'subject' => 'A ticket just assinged to you [Ticket ID: :ticket_id] :subject',
        'greeting' => 'Hello :user',
        'message' => 'This is a notification to let you know that ticket [Ticket ID: :ticket_id] :subject just assinged to you. Review and reply the ticket to as soon as possible.',
        'button_text' => 'Reply the ticket',
    ],

    'ticket_replied' => [
        'subject' => ':user replied the ticket [Ticket ID: :ticket_id] :subject',
        'greeting' => 'Hello :user',
        'message' => ':reply',
        'button_text' => 'View the ticket',
    ],

    'ticket_updated' => [
        'subject' => 'A Ticket [Ticket ID: :ticket_id] :subject has been updated!',
        'greeting' => 'Hello :user!',
        'message' => 'One of your support tickets ticket ID #:ticket_id :subject has been updated. Please contact us if you need any assistance.',
        'button_text' => 'View the ticket',
    ],

    // User Notifications
    'user_created' => [
        'subject' => ':admin added you to the :marketplace marketplace!',
        'greeting' => 'Congratulation :user!',
        'message' => 'You have been added to the :marketplace by :admin! Click the button below to login into your account. Use the temporary password for initial login.',
        'alert' => 'Don\'t forgot to change your password after login.',
        'button_text' => 'Visit your profile',
    ],
    'user_updated' => [
        'subject' => 'Account information updated successfully!',
        'greeting' => 'Hello :user!',
        'message' => 'This is a notification to let you know that your account has been updated successfully!',
        'button_text' => 'Visit your profile',
    ],

    // Vendor Notifications
    'verdor_registered' => [
        'subject' => 'New vendor just registered!',
        'greeting' => 'Congratulation!',
        'message' => 'Your marketplace :marketplace just got a new verdor with shop name <strong>:shop_name</strong> and the merchant\'s email address is :merchant_email',
        'button_text' => 'Go to the Dashboard',
    ],

    // User/Merchant Notification
    'email_verification' => [
        'subject' => 'Verify your :marketplace account!',
        'greeting' => 'Congratulation :user!',
        'message' => 'Your account has been created successfully! Click the button below to verify your email address.',
        'button_text' => 'Verify My Email',
    ],

    // Version 1.2.6
    'dispute_solved' => [
        'subject' => 'Dispute [Order ID: :order_id] has been marked as solved!',
        'greeting' => 'Hello :customer!',
        'message' => 'The dispute for Order ID: :order_id has been marked as solved. Thank you for being with us.',
        'button_text' => 'View the dispute',
    ],

    // Version 2.1.0
    'new_chat_message' => [
        'subject' => 'New message via live chat form :sender',
        'greeting' => 'Hello :receipent !',
        'message' => 'You got a new message via the live chat window ":message". Please login into the shop dashboard to reply.',
        'button_text' => 'View on dashboard',
    ],

];
