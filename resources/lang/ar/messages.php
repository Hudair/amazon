<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to display messages for any action, notiches and warnings.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'canceled'          => 'Canceled!',
    'confirmed'         => 'Confirmed',
    'created'           => ':model has been created successfully!',
    'imported'          => ':model has been imported successfully!',
    'sent'              => ':model has been sent successfully!',
    'updated'           => ':model has been updated successfully!',
    'trashed'           => ':model has been moved to trash!',
    'restored'          => ':model has been restored successfully!',
    'deleted'           => ':model has been deleted permanently!',
    'card_updated' => 'Credit card updated successfully!',
    'demo_restriction' => 'This action is restricted for the demo mode!',
    'subscription_cancelled' => 'Subscription has been cancelled!',
    'subscription_resumed' => 'Subscription resumed successfully!',
    'subscribed' => 'Congratulation! Subscribed successfully!!',
    'subscription_error' => 'Error creating subscription. Please contact customer support.',
    'cant_delete_faq_topic' => 'Can\'t delete: Please delete all FAQs under the :topic and try again!',
    'nofound'           => ':model is not exist! try another search.',
    'file_deleted'      => 'The file has been deleted successfully!',
    'updated_featured_categories' => 'Featured category list updated successfully!',
    'archived'          => ':model has been archived successfully!',
    'fulfilled'         => 'The order has been fulfilled successfully.',
    'fulfilled_and_archived' => 'The order has been fulfilled and archived successfully.',
    'failed'            => 'The action has been failed! Something went wrong!!',
    'input_error'       => 'There were some problems with your input.',
    'secret_logged_in'  => 'Impersonated successfully.',
    'secret_logged_out' => 'Logged out from secret account.',
    'you_are_impersonated' => 'You are impersonated into the current account. Be careful about your actions.',
    'profile_updated'   => 'You account has been updated successfully!',
    'password_updated'  => 'You account password has been updated successfully!',
    'incorrect_current_password' => 'Your current password is not correct. Please try again!',
    'file_not_exist' => 'Requested file does not exist!',
    'img_upload_failed' => 'Image upload has been failed!',
    'payment_method_activation_success' => 'Activated successfully! Now you can accept payment using this method.',
    'payment_method_activation_failed' => 'Payment method activation failed! Please try again.',
    'payment_method_disconnect_success' => 'Disconnected successfully!',
    'payment_method_disconnect_failed' => 'This application is not connected to the stripe account, or the account does not exist.',
    'invoice_sent_to_customer' => 'The invoice has been sent to the customer.',
    'freezed_model' => 'This record is frozen by the system config. The application needs this value to run properly.',
    'email_verification_notice' => 'Your email address is not verified, please verify to get full access.',
    'theme_activated' => 'Theme :theme has been activated successfully!',
    'the_ip_banned' => 'The IP address has been banned from the application.',
    'the_ip_unbanned' => 'The IP address has been removed from blocklist.',

    'no_billing_info' => 'Please add billing information to continue.',
    'no_card_added' => 'Please add billing information to subscribe.',
    'we_dont_save_card_info' => 'We do not store your card information.',
    'plan_comes_with_trial' => 'Every plan comes with a FREE :days days trial period',
    'trial_ends_at' => 'Your trial ends in :ends days!',
    'trial_expired' => 'Your trial period expired! Choose a subscription to continue.',
    'generic_trial_ends_at' => 'Your free trial ends in :ends days! Add billing information and choose a plan to continue.',
    'resume_subscription' => 'Your subscription ends in :ends days! Resume your subscription to continue.',
    'choose_subscription' => 'Choose a subscription that best fit your need.',
    'trouble_validating_card' => 'We had trouble validating your card. It can be your card provider is preventing us from charging the card. Please contact your card provider or customer support.',
    'subscription_expired' => 'Your subscription has been expired! Choose a subscription to continue.',
    'using_more_resource' => 'You\'re using more resources than the :plan plan allowed to. Please use a plan that appropriate to your business.',
    'cant_add_more_user' => 'Your current subscription plan doesn\'t allow to add more user. If your business need more user to manage, please upgrade your plan.',
    'cant_add_more_inventory' => 'You have reached the maximum limit of stock allocate to your current subscription plan. Please upgrade your plan to extend the limit.',
    'time_to_upgrade_plan' => 'It\'s good time to updgreade your plan',
    'only_merchant_can_change_plan' => 'Only the shop owner can change the billing/subscription plan.',
    'message_send_failed' => 'Sorry, the message cannot be sent now! Please try again later.',
    'resource_uses_out_of' => ':used of :limit',
    'cant_charge_application_fee' => 'When vendors get paid directly, you can\'t charge <b>marketplace commission</b> and per <b>transaction fee</b> with this payment method. With the zCart wallet module you can charge the marketplace commission and transaction fee.',
    'license_uninstalled' => 'License uninstalled.',
    'license_updated' => 'License updated.',
    'take_a_backup' => 'You can take a database backup snapshot. Make sure you have configured the backup preferences before take this action. Make sure the <code>mysqldump</code> is installed on your server. Check the documentation for help.',
    'backup_done' => 'Backup done successfully!',

    // 'you_have_disputes_solve' => 'There are :disputes active disputes! Please review and solve disputes.',
    // 'you_have_refund_request' => 'You have :requests refund request. Please review and take action.',

    // 'action_failed'    => [
    //     'create'   => 'Create :model has been failed!',
    //     'update'   => 'Update :model has been failed!',
    //     'trash'   => ':model has been moved to trash!',
    //     'restore'  => ':model has been restored failed!',
    //     'delete'   => ':model has been deleted failed!',
    // ],

    'inventory_exist'   => 'The product is already exist in your inventory. Please update the existing list instead of creating duplicate list.',
    'inventory_not_found'   => 'The product is not found in your inventory. Please update the inventory and try again.',

    'notice' => [
        'no_billing_address' => 'This customer has no billing address set up yet. Please add a billing address before create an order.',

        'no_active_payment_method' => 'Your store has no active payment method. Please activate at least one payment method to accept order.',

        'no_shipping_option_for_the_zone' => 'No shipping zone available for this area. Please create a new shipping zone or add this shipping area to an existing zone.',

        'no_rate_for_the_shipping_zone' => 'The <strong> :zone </strong> shipping zone has no shipping rates. Please create shipping rates to accept orders from this zone.',

        'cant_cal_weight_shipping_rate' => 'Can\'t calculate weight based shipping rate. Because weight are not set for some items.',
    ],

    'no_changes' => 'Nothing to show',
    'no_orders' => 'No order found!',
    'no_history_data' => 'No information to show',
    'this_slug_taken' => 'This slug has been taken! Try a new one.',
    'slug_length' => 'The slug should be minimum three character long.',
    'message_count' => 'You have :count messages',
    'notification_count' => 'You have :count unread notifications',
    'alert' => 'Alert!',
    'dispute_appealed' => 'A dispute appealed',
    'appealed_dispute_replied' => 'New reply for appealed dispute',
    'thanks' => 'Thanks',
    'regards' => 'Regards , ',
    'ticket_id' => 'Ticket ID',
    'category' => 'Category',
    'subject' => 'Subject',
    'prioriy' => 'Prioriy',
    'amount' => 'Amount',
    'shop_name' => 'Shop name',
    'customer_name' => 'Customer name',
    'shipping_address' => 'Shipping address',
    'billing_address' => 'Billing address',
    'shipping_carrier' => 'Shipping carrier',
    'tracking_id' => 'Tracking ID',
    'order_id' => 'Order ID',
    'payment_method' => 'Payment method',
    'payment_status' => 'Payment status',
    'order_status' => 'Order status',
    'status' => 'Status',
    'unread_notification' => 'Unread notification',
    'profile_updated' => 'Profile updated',
    'system_is_live' => 'Marketplace is back to LIVE!',
    'system_is_down' => 'The marketplace goes DOWN!',
    'system_config_updated' => 'System configuration updated!',
    'system_info_updated' => 'System information updated!',
    'temp_password' => 'Temporary Password: :password',
    'password_updated' => 'Password has been changed!',
    'shop_created' => 'The shop :shop_name has been created!',
    'shop_updated' => 'Shop information has been updated!',
    'shop_config_updated' => 'Shop configuration has been updated!',
    'shop_down_for_maintainace' => 'Shop goes DOWN!',
    'shop_is_live' => 'Shop is back to LIVE!',
    'ticket_replied' => 'Ticket has been replied',
    'ticket_updated' => 'Ticket has been updated',
    'ticket_created' => 'A new ticket has been created',
    'ticket_assigned' => 'A new ticket has been assigned to you',
    'we_will_get_back_to_you_soon' => 'Send us a message and we will get back to you soon!',
    'faqs' => 'Most Frequently Asked Questions',
    'how_the_marketplace_works' => 'It\'s Good To Know How The System Works, Before You Register',
    'merchant_benefits' => 'Selling online has never been easier.',
    'import_ignored' => 'Some rows has been ignored! Please check the information and import again.',
    'import_demo_contents' => 'Importing demo contents will remove all data from the database and reset all configurations except the file <small>(the .env and other config files in configs/ directory)</small> configurations . The system will go back to a fresh installation. <br/><b>You can\'t revert this action.</b>',
    'imported' => 'Imported successfully!',
    'env_saved' => 'The .env file saved successfully!',
    'modify_environment_file' => 'Be careful when working with .env file. This the main configuration file and the system will break down if you do anything wrong here. Take a backup every time you want to make any modification. <br/><b>You can\'t revert this action.</b>',

    'be_careful_sensitive_area' => 'Be careful about your actions! This configurations are very sensitive and the system may breaks if you do anything wrong here. <br/><b>You can\'t revert some of these actions.</b>',

    'unfulfilled_percents' => ':percent% of today\'s total orders',
    'update_app_license' => 'Updates the license if IP address of your server was changed, so script continues to work on new IP.',
    'uninstall_app_license' => 'Uninstalling the license will allow you to re-install the script and the current installation will stop working immediately. Please remove the old installation files to avoid unexpected issues with new installation.<br/><b>You can\'t revert this action.</b>',
    'last_30_days_percents' => ':percent% :state in 30 days',
    'stock_out_percents' => ':percent% of total :total items',
    'todays_sale_percents' => ':percent% :state from yesterday',
    'no_sale' => 'No sale :date',
    'loogedin_as_admin' => 'You\'re already logged in as an admin user.',

    'permission'        => [
        'denied'        => 'Permission denied!',
    ],

    // Version 1.2.4
    'listings_not_visible' => 'Your listings are not visible on the front end. Because: :reason',
    'no_active_payment_method' => 'Your store has no active payment method.',
    'no_active_shipping_zone' => 'Your store has no active shipping zone. Please create shipping zones to accept order.',

    // Version 1.2.5
    'your_shop_in_hold' => 'Your store is on hold! We will review and approve your store as soon as possible!',
    'youe_shop_in_maintenance_mode' => 'The shop in maintenance mode.',

    // Version 1.3.0
    'how_id_verification_helps' => 'How Identity Verification Helps',

    'how_the_verification_process_works' => 'How the process works',

    'subscription_updated' => 'Subscription has been updated successfully!',

    'subscription_update_failed' => 'Subscription update has been failed! Please see the log file for details.',

    'misconfigured_google_analytics' => 'The Google Analytics report is enabled but not configured or misconfigured! Please check the documentation for help.',

    'pending_approvals' => '[0,1] :count Pending approval need action|[2,*] :count Pending approvals need action',

    'pending_verifications' => '[0,1] :count Pending verification need action|[2,*] :count Pending verifications need action',

    'verification_intro' => 'Once you are verified, we will show the <strong>verified</strong> badge on your business and on your store profile page. This lets your business build trust on the marketplace.',

    'verification_process' => '<ul>
            <li>You take a picture or scan your ID (passport, driving license or government issued ID) using a HD camera and upload</li>
            <li>Upload proof of your address (driving license, property tax receipt, utility bill or lease agreement)</li>
            <li>You take or upload a picture of your face.</li>
            <li>We will check that they are pictures of the same person.</li>
            <li>You can not use same documentation(driving license) for ID and Address verification.</li>
        </ul>',

    'what_the_verification_documents_need' => 'What formal identity documents do I need?',

    'verification_documents' => 'You can use: <ul>
            <li>your passport</li>
            <li>your driving license</li>
            <li>a government issued ID.</li>
            <li>property tax receipt</li>
            <li>utility bill</li>
            <li>lease agreement</li>
        </ul>
        Driving licenses and government issued IDs must be made out of plastic. All IDs must be valid.',

    'verified_business_name_like' => 'Your business name will be shown like this',

    // Version 1.3.3
    'csv_import_process_started' => 'The data has been submitted successfully. The process may take a few minimums. You\'ll get an email when it\'s done.',

    'model_has_association' => 'The :model has :associate in it. To delete this :model, please remove all :associate under the :model',

    // Version 1.4.0
    'active_worldwide_business_area' => 'The status will not affect as the marketplace business area is set to worldwide! To change the business area settings please check the configuration section.',

    'please_select_conversation' => 'Please select a conversation from the left.',

    'session_expired' => 'Your session has been expired! Please login.',

    'no_address_for_invoice' => 'You have no business address set up yet. Please add address now.',

    'package_settings_updated' => 'Package settings updated',

    'next_billing_date' => 'Your next subscription billing date is <strong>:date</strong> Please keep sufficient balance on your wallet to keep going.',

    'package_installed_success' => 'The :package module has been installed successfully!',

    'package_installed_already' => 'The :package module is already installed!',

    'package_uninstalled_success' => 'The :package module has been uninstalled successfully!',

    'cancellation_require_admin_approval' => 'Cancellation require admin approval. A cancellation fee may applied.',

    'a_cancellation_fee_be_charged' => 'A cancellation fee of <strong>:fee</strong> will be charged',

    'order_will_be_cancelled_instantly' => 'The order will be cancelled instantly.',

    'not_accessible_on_demo' => 'This content is not accessible on the demo mode!',

    'updated_deal_of_the_day' => 'Deal of the day updated successfully.',

    'updated_tagline' => 'Tagline updated successfully.',

    'featured_brands_updated' => 'Featured brands updated successfully.',

    'featured_items_updated' => 'Featured items updated successfully.',

    'best_finds_under_updated' => 'Best finds under updated successfully.',

    'trending_now_category_updated' => 'Trending now category updated successfully.',

    'trending_categories_update_failed' => 'You can add maximum :limit trending category',

    'package_inactive' => ':package is inactive, please activate from admin panel.',

    'misconfigured_subscription_stripe' => 'The system found misconfigured Stripe subscriptions. Please check your settings. Read the documentation if need help.',

    'misconfigured_subscription_wallet' => 'Wallet based subscription required WALLET and LOCAL SUBSCRIPTION packages to fucntion. Please contact support team for help. System will try to use STRIPE subscription unless these requirements met.',

    'some_item_out_of_stock' => 'Few items are not available right now. We\'ve added all available item',

    'dependent_package_failed' => 'Plugin dependency failed! need to enable and configure the :dependency module(s).',

    'misconfigured_plugin' => 'The :package needs to configure correctly! Please check the documentation and configure it correctly or contact support if need help.',

    'misconfigured_payment' => 'The :payment is misconfigured and needs to configure correctly! Please check the documentation and configure it correctly or contact support if need help.',

    'manual_payment_configure_help' => 'Set the manual payment instructions on settings. Without this configuration manual payment will not work.',
    'confirm_regenerate_key' => "Regenerating the application keys will enforce you to rebuild your application apps otherwise your apps won'n work",
    'custom_css_added' => 'Custom css added successfully',
    'custom_css_updated' => 'Custom css updated successfully',
    'custom_css_deleted' => 'Custom css deleted successfully',
];
