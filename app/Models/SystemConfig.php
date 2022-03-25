<?php

namespace App\Models;

use App\Common\SystemUsers;
use App\Exceptions\PluginFailed;

class SystemConfig extends BaseModel
{
    use SystemUsers;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'systems';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trial_days',
        'required_card_upfront',
        'vendor_needs_approval',
        'support_phone',
        'support_phone_toll_free',
        'support_email',
        'default_sender_email_address',
        'default_email_sender_name',
        'length_unit',
        'weight_unit',
        'valume_unit',
        // 'date_format',
        // 'date_separator',
        // 'time_format',
        // 'time_separator',
        'decimals',
        'decimalpoint',
        'thousands_separator',
        'show_currency_symbol',
        'show_space_after_symbol',
        'coupon_code_size',
        'gift_card_serial_number_size',
        'gift_card_pin_size',
        'max_img_size_limit_kb',
        'max_number_of_inventory_imgs',
        'active_theme',
        'pagination',
        'show_seo_info_to_frontend',
        'show_address_title',
        'address_show_country',
        'address_show_map',
        'address_default_country',
        'address_default_state',
        'allow_guest_checkout',
        'auto_approve_order',
        'ask_customer_for_email_subscription',
        'vendor_can_view_customer_info',
        'can_use_own_catalog_only',
        'notify_when_vendor_registered',
        'notify_when_dispute_appealed',
        'notify_new_message',
        'notify_new_ticket',
        'facebook_link',
        'google_plus_link',
        'twitter_link',
        'pinterest_link',
        'instagram_link',
        'youtube_link',
        'google_analytic_report',
        'enable_chat',
        'can_cancel_order_within',
        'vendor_order_cancellation_fee',
        'show_merchant_info_as_vendor',
        'show_item_conditions',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'required_card_upfront' => 'boolean',
        'vendor_needs_approval' => 'boolean',
        'allow_guest_checkout' => 'boolean',
        'auto_approve_order' => 'boolean',
        'vendor_can_view_customer_info' => 'boolean',
        'ask_customer_for_email_subscription' => 'boolean',
        'notify_when_vendor_registered' => 'boolean',
        'notify_when_dispute_appealed' => 'boolean',
        'notify_new_message' => 'boolean',
        'notify_new_ticket' => 'boolean',
        'show_currency_symbol' => 'boolean',
        'show_space_after_symbol' => 'boolean',
        'show_address_title' => 'boolean',
        'address_show_country' => 'boolean',
        'address_show_map' => 'boolean',
        'google_analytic_report' => 'boolean',
        'enable_chat' => 'boolean',
        'show_seo_info_to_frontend' => 'boolean',
        'can_use_own_catalog_only' => 'boolean',
        'show_merchant_info_as_vendor' => 'boolean',
        'show_item_conditions' => 'boolean',
    ];

    /**
     * Check if Ggoogle Analytic enabled.
     *
     * @return bool
     */
    public static function isGgoogleAnalyticEnabled()
    {
        return (bool) config('system_settings.google_analytic_report');
    }

    /**
     * Check if Ggoogle Analytic has been Configured.
     *
     * @return bool
     */
    public static function isGgoogleAnalyticConfigured()
    {
        return (bool) config('analytics.view_id') && file_exists(config('analytics.service_account_credentials_json'));
    }

    /**
     * Check if Ggoogle Analytic has been Ready.
     *
     * @return bool
     */
    public static function isGgoogleAnalyticReady()
    {
        return self::isGgoogleAnalyticEnabled() && self::isGgoogleAnalyticConfigured();
    }

    /**
     * Check if Chat enabled.
     *
     * @return bool
     */
    public static function isChatEnabled()
    {
        return (bool) config('system_settings.enable_chat');
    }

    /**
     * Check if newsletter has been Configured.
     *
     * @return bool
     */
    public static function isNewsletterConfigured()
    {
        return (bool) config('newsletter.apiKey') && config('newsletter.lists.subscribers.id');
    }

    /**
     * Check if vendor subscription billing configured for wallet
     *
     * @return bool
     */
    public static function isBillingThroughWallet()
    {
        if (config('system.subscription.billing') == 'wallet') {
            $dependencies = ['wallet', 'subscription'];

            if (is_incevio_package_loaded($dependencies)) {
                return true;
            }

            throw new PluginFailed(trans('messages.dependent_package_failed', ['dependency' => implode(',', $dependencies)]));
        }

        return false;
    }

    /**
     * Check if give payment method is configured for platform
     * Mainly used in wallet module deposit
     *
     * @return bool
     */
    public static function isPaymentConfigured($code)
    {
        switch ($code) {
            case 'stripe':
                return (bool) (config('services.stripe.key') && config('services.stripe.client_id') && config('services.stripe.secret'));

            case 'paypal-express':
                return (bool) (config('services.paypal.client_id') && config('services.paypal.secret'));

            case 'paypal-marketplace':
                return (bool) (config('paypalMarketplace.api.client_id') && config('paypalMarketplace.api.secret'));

            case 'instamojo':
                return (bool) (config('services.instamojo.api_key') && config('services.instamojo.auth_token'));

            case 'authorize-net':
                return (bool) (config('services.authorize-net.api_login_id') && config('services.authorize-net.transaction_key'));

            case 'cybersource':
                return (bool) (config('services.cybersource.merchant_id') && config('services.cybersource.api_key_id') && config('services.cybersource.secret'));

            case 'paystack':
                return (bool) (config('services.paystack.public_key') && config('services.paystack.secret'));

            case 'razorpay':
                return (bool) (config('razorpay.merchant.api_key') && config('razorpay.merchant.secret'));

            case 'jrfpay':
                return (bool) (config('jrfpay.merchant.id') && config('jrfpay.merchant.key'));

            case 'mpesa':
                return (bool) (config('mpesa.api.consumer_key') && config('mpesa.api.consumer_secret'));

            case 'zcart-wallet':
                return customer_has_wallet();

            case 'wire':
            case 'cod':
                return (bool) get_from_option_table('wallet_payment_info_' . $code);
                // return is_incevio_package_loaded('wallet') && (bool) get_from_option_table('wallet_payment_info_' . $code);
        }

        return null;
    }
}
