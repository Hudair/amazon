<?php

namespace App\Models;

// use Konekt\PdfInvoice\InvoicePrinter;
use App\Common\Attachable;
use App\Common\Loggable;
use App\Events\Order\OrderCancellationRequestApproved;
use App\Events\Order\OrderCancelled;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderUpdated;
use App\Jobs\AdjustQttForCanceledOrder;
use App\Services\PdfInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Order extends BaseModel
{
    use HasFactory, SoftDeletes, Loggable, Attachable;

    const STATUS_WAITING_FOR_PAYMENT = 1;    // Default
    const STATUS_PAYMENT_ERROR = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_FULFILLED = 4;   // All status value less than this consider as unfulfilled
    const STATUS_AWAITING_DELIVERY = 5;
    const STATUS_DELIVERED = 6;
    const STATUS_RETURNED = 7;
    const STATUS_CANCELED = 8;
    const STATUS_DISPUTED = 9;

    const PAYMENT_STATUS_UNPAID = 1;       // Default
    const PAYMENT_STATUS_PENDING = 2;
    const PAYMENT_STATUS_PAID = 3;      // All status before paid value consider as unpaid
    const PAYMENT_STATUS_INITIATED_REFUND = 4;
    const PAYMENT_STATUS_PARTIALLY_REFUNDED = 5;
    const PAYMENT_STATUS_REFUNDED = 6;

    const FULFILMENT_TYPE_DELIVER = 'deliver';
    const FULFILMENT_TYPE_PICKUP = 'pickup';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'shipping_date' => 'datetime',
        'delivery_date' => 'datetime',
        'payment_date' => 'datetime',
        'goods_received' => 'boolean',
    ];

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'shop_id',
        'customer_id',
        'ship_to',
        'shipping_zone_id',
        'shipping_rate_id',
        'packaging_id',
        'item_count',
        'quantity',
        'shipping_weight',
        'taxrate',
        'total',
        'discount',
        'shipping',
        'packaging',
        'handling',
        'taxes',
        'grand_total',
        'billing_address',
        'shipping_address',
        'shipping_date',
        'delivery_date',
        'tracking_id',
        'coupon_id',
        'carrier_id',
        'message_to_customer',
        'send_invoice_to_customer',
        'admin_note',
        'buyer_note',
        'payment_method_id',
        'payment_instruction',
        'payment_ref_id',
        'payment_date',
        'payment_status',
        'order_status_id',
        'goods_received',
        'approved',
        'feedback_id',
        'disputed',
        'email',
        'customer_phone_number',
        'fulfilment_type',
        'device_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
    ];

    /**
     * Get the country associated with the order.
     */
    public function shipTo()
    {
        return $this->belongsTo(Address::class, 'ship_to');
        // return $this->belongsTo(Country::class, 'ship_to');
    }

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => trans('app.guest_customer'),
        ]);
    }

    /**
     * Get the shop associated with the order.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class)->withDefault();
    }

    /**
     * Get the coupon associated with the order.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class)->withDefault();
    }

    /**
     * Get the tax associated with the order.
     */
    public function tax()
    {
        return $this->shippingRate->shippingZone->tax();
    }

    /**
     * Get the carrier associated with the cart.
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class)->withDefault();
    }

    /**
     * Get the items associated with the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the inventories for the order.
     */
    public function inventories()
    {
        // return $this->belongsToMany(Inventory::class, 'order_items')->using(OrderItem::class)
        // ->withPivot(['item_description', 'quantity', 'unit_price','feedback_id'])->withTimestamps();

        return $this->belongsToMany(Inventory::class, 'order_items')
            ->withPivot(['item_description', 'quantity', 'unit_price', 'feedback_id'])
            ->withTimestamps();
    }

    // public function inventories()
    // {
    //     return $this->belongsToMany(Inventory::class, 'order_items')
    //     ->withPivot('item_description', 'quantity', 'unit_price','feedback_id')->withTimestamps();
    // }

    /**
     * Return collection of conversation related to the order
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function conversation()
    {
        return $this->hasOne(Message::class, 'order_id');
    }

    /**
     * Return collection of refunds related to the order
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the dispute for the order.
     */
    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }

    /**
     * Get the cancellation request for the order.
     */
    public function cancellation()
    {
        return $this->hasOne(Cancellation::class);
    }

    /**
     * Get the shippingRate for the order.
     */
    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class, 'shipping_rate_id')->withDefault();
    }

    /**
     * Get the shippingZone for the order.
     */
    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id')->withDefault();
    }

    /**
     * Get the paymentMethod for the order.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
        // ->withDefault();
    }

    /**
     * This function returns delivery boy associated with order
     * @return [delivery_boy]
     */
    public function deliveryBoy(): BelongsTo
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    /**
     * Get the packaging for the order.
     */
    public function shippingPackage()
    {
        return $this->belongsTo(Packaging::class, 'packaging_id')->withDefault();
    }

    /**
     * Get the status for the order.
     */
    // public function status()
    // {
    //     return $this->belongsTo(OrderStatus::class, 'order_status_id')->withDefault();
    // }

    /**
     * Get the shop feedback for the order/shop.
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id')->withDefault();
    }

    /**
     * Set tag date formate
     */
    public function setShippingDateAttribute($value)
    {
        $this->attributes['shipping_date'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function setDeliveryDateAttribute($value)
    {
        $this->attributes['delivery_date'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function setShippingAddressAttribute($value)
    {
        $this->attributes['shipping_address'] = is_numeric($value) ? Address::find($value)->toString(true) : $value;
    }

    public function setBillingAddressAttribute($value)
    {
        $this->attributes['billing_address'] = is_numeric($value) ? Address::find($value)->toString(true) : $value;
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchived($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithArchived($query)
    {
        return $query->withTrashed();
    }

    /**
     * Scope a query to only include active orders.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMine($query)
    {
        return $query->where('shop_id', Auth::user()->merchantId());
    }

    /**
     * Scope a query to only include paid orders.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', '>=', static::PAYMENT_STATUS_PAID);
    }

    /**
     * Scope a query to only include unpaid orders.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', '<', static::PAYMENT_STATUS_PAID);
    }

    /**
     * Scope a query to only include unfulfilled orders.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnfulfilled($query)
    {
        return $query->where('order_status_id', '<', static::STATUS_FULFILLED);
    }

    /**
     * Return all the orders which are not delivered yet
     *
     * @return [not delivered orders]
     */
    public function scopeToDeliver(Builder $query)
    {
        return $query->where('order_status_id', '<=', static::STATUS_DELIVERED);
    }

    /**
     * Return all the orders which are not delivered yet
     *
     * @return [not delivered orders]
     */
    public function scopeMyDelivery(Builder $query)
    {
        return $query->where('delivery_boy_id', Auth::guard('delivery_boy-api')->id())
            ->oldest();
    }

    /**
     * Return shipping cost with handling fee
     *
     * @return number
     */
    public function get_shipping_cost()
    {
        return $this->shipping + $this->handling;
    }

    /**
     * Calculate and Return grand tolal
     *
     * @return number
     */
    public function calculate_grand_total()
    {
        return ($this->total + $this->handling + $this->taxes + $this->shipping + $this->packaging) - $this->discount;
    }

    public function grand_total_for_paypal()
    {
        return ($this->calculate_total_for_paypal() + format_price_for_paypal($this->handling) + format_price_for_paypal($this->taxes) + format_price_for_paypal($this->shipping) + format_price_for_paypal($this->packaging)) - format_price_for_paypal($this->discount);
    }

    public function calculate_total_for_paypal()
    {
        $total = 0;
        $items = $this->inventories->pluck('pivot');

        foreach ($items as $item) {
            $total += (format_price_for_paypal($item->unit_price) * $item->quantity);
        }

        return format_price_for_paypal($total);
    }

    /**
     * Check if the order has been paid
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->payment_status >= static::PAYMENT_STATUS_PAID;
    }

    /**
     * Check if the order has been Fulfilled
     *
     * @return bool
     */
    public function isFulfilled()
    {
        return $this->order_status_id >= static::STATUS_FULFILLED;
    }

    /**
     * Check if the order has been Canceled
     *
     * @return bool
     */
    public function isDelivered()
    {
        return $this->order_status_id >= static::STATUS_DELIVERED;
    }

    /**
     * Check if the order has been Canceled
     *
     * @return bool
     */
    public function isCanceled()
    {
        return $this->order_status_id == static::STATUS_CANCELED;
    }

    /**
     * Check if the order has been requested to canceled
     *
     * @return bool
     */
    public function hasPendingCancellationRequest()
    {
        return !$this->isCanceled() && $this->cancellation && $this->cancellation->isOpen();
    }

    public function hasClosedCancellationRequest()
    {
        return $this->cancellation && $this->cancellation->isClosed();
    }

    /**
     * Check if the order has been archived
     *
     * @return bool
     */
    public function isArchived()
    {
        return $this->deleted_at !== null;
    }

    public function refundedSum()
    {
        return $this->refunds->where('status', Refund::STATUS_APPROVED)->sum('amount');
    }

    // Update the goods_received field when customer confirm or change status
    public function mark_as_goods_received()
    {
        return $this->update([
            'order_status_id' => static::STATUS_DELIVERED,
            'goods_received' => 1
        ]);
    }

    // Update the feedback_given field when customer leave feedback for the shop
    public function feedback_given($feedback_id = null)
    {
        return $this->update(['feedback_id' => $feedback_id]);
    }

    // public function markAsFulfilled()
    // {
    //     $this->forceFill(['order_status_id' => static::STATUS_FULFILLED])->save();
    // }

    /**
     * Return Tracking Url for the order
     *
     * @return str/Null
     */
    public function getTrackingUrl()
    {
        if ($this->carrier_id && $this->tracking_id && $this->carrier->tracking_url) {
            return str_replace('@', $this->tracking_id, $this->carrier->tracking_url);
        }

        return null;
    }

    /**
     * Check if the order has been Canceled
     *
     * @return bool
     */
    public function canBeCanceled()
    {
        $minutes = config('system_settings.can_cancel_order_within');

        // Not allowed to cancel
        if ($minutes === 0) {
            return false;
        }

        // Allowed untill fulfilment
        if ($minutes === null) {
            return $this->canRequestCancellation();
        }

        return $this->canRequestCancellation() && $this->created_at->addMinutes($minutes) > Carbon::now();
    }

    /**
     * Check if the order has been Canceled
     *
     * @return bool
     */
    public function canRequestCancellation()
    {
        return !$this->isCanceled() && !$this->isFulfilled() && !$this->cancellation;
    }

    /**
     * Check if the order is cancellationFeeApplicable
     *
     * @return bool
     */
    public function cancellationFeeApplicable()
    {
        return $this->isPaid() && can_set_cancellation_fee() &&
            (!config('system_settings.vendor_order_cancellation_fee') ||
                config('system_settings.vendor_order_cancellation_fee') > 0);
    }

    /**
     * Check if the order can be returned
     *
     * @return bool
     */
    public function canRequestReturn()
    {
        if ($this->cancellation) {
            return $this->isDelivered() && !$this->cancellation->return_goods;
        }

        return $this->isDelivered() && !$this->isCanceled();
    }

    /**
     * Check if the order has been tracked
     *
     * @return bool
     */
    public function canTrack()
    {
        return false; // Because the plagin not working

        return $this->isFulfilled() && $this->tracking_id && !$this->isDelivered();
    }

    /**
     * Check if this order can still be evaluated
     *
     * @return bool
     */
    public function canEvaluate()
    {
        // Return if goods are not received yet
        if (!$this->goods_received) {
            return false;
        }

        // Check if the shop has been rated yet
        if (!$this->feedback_id) {
            return true;
        }

        // Check if all items are been rated yet
        foreach ($this->inventories as $item) {
            if (!$item->pivot->feedback_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render PDF invoice
     *
     * @param  string $des I => Display on browser, D => Force Download, F => local path save, S => return document as string
     */
    public function invoice($des = 'D')
    {
        // Temporary solution
        $local = App::getLocale(); // Get current local
        App::setLocale('en'); // Set local to en

        $invoiceTo = multi_tag_explode([',', '<br/>'], strip_tags($this->billing_address, '<br>'));
        $invoiceTo = array_filter(array_map('trim', $invoiceTo));

        array_unshift($invoiceTo, $this->customer->name);

        $vendorAddress = $this->shop->primaryAddress ?? $this->shop->address;

        $invoiceFrom = $vendorAddress ? $vendorAddress->toArray() : [];

        // Replace the address type with vendor shop name
        $invoiceFrom['address_type'] = $this->shop->legal_name;

        // Reset the array keys
        $invoiceFrom = array_values($invoiceFrom);

        $title = (bool) config('invoice.title') ?
            config('invoice.title') :
            trans('invoice.invoice');

        $invoice = new PdfInvoice();
        // $invoice->AddFont('NotoMono', '', '/fonts/NotoMono/NotoMono-Regular.ttf', true);
        // $invoice->SetFont('NotoMono', '', 14);

        $invoice->setColor(config('invoice.color', '#007fff'));      // pdf color scheme
        $invoice->setDocumentSize(config('invoice.size', 'A4'));      // set document size
        $invoice->setType($title);    // Invoice Type

        // Set logo image if exist
        $logo_path = optional($this->shop->logoImage)->path;
        if (App::environment('production') && Storage::exists($logo_path)) {
            $invoice->setLogo(get_storage_file_url($logo_path, null));
        }

        $invoice->setReference($this->order_number);   // Reference
        $invoice->setDate($this->created_at->format('M d, Y'));   //Billing Date
        $invoice->setTime($this->created_at->format('h:i:s A'));   //Billing Time
        // $invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        $invoice->setFrom($invoiceFrom);
        $invoice->setTo($invoiceTo);

        foreach ($this->inventories as $item) {
            $item_description = $item->pivot->item_description;

            if ($this->cancellation && $this->cancellation->isItemInRequest($item->id)) {
                // continue;
                $item_description = substr($item_description, 0, 20) . '... [' . trans('theme.' . $this->cancellation->request_type . '_requested') . ' ]';
            }

            $invoice->addItem($item_description, '', $item->pivot->quantity, $item->pivot->unit_price);
        }

        $invoice->addSummary(trans('invoice.total'), $this->total);

        if ($this->taxes) {
            $invoice->addSummary(trans('invoice.taxes') . ' ' . get_formated_decimal($this->taxrate, true, 2) . '%', $this->taxes);
        }

        if ($this->packaging) {
            $invoice->addSummary(trans('invoice.packaging'), $this->packaging);
        }

        if ($this->handling) {
            $invoice->addSummary(trans('invoice.handling'), $this->handling);
        }

        if ($this->shipping) {
            $invoice->addSummary(trans('invoice.shipping'), $this->shipping);
        }

        if ($this->discount) {
            $invoice->addSummary(trans('invoice.discount'), $this->discount);
        }

        $invoice->addSummary(trans('invoice.grand_total'), $this->grand_total, true);

        $invoice->addBadge($this->paymentStatusName(true));

        if (config('invoice.company_info_position') == 'right') {
            $invoice->flipflop();
        }

        if ($this->message_to_customer) {
            $invoice->addTitle(trans('invoice.message'));
            $invoice->addParagraph($this->message_to_customer);
        }

        $invoice->setFooternote(get_platform_title() . ' | ' . url('/') . ' | ' . trans('invoice.footer_note'));

        $invoice->render(get_platform_title() . '-' . $this->order_number . '.pdf', $des);

        // Temporary!
        App::setLocale($local); //Set local to the curret local
    }

    /**
     * Cancel the order
     *
     * @return void
     */
    public function cancel($partial = false, $cancellation_fee = null)
    {
        // Sync up the inventory. Increase the stock of the order items from the listing
        AdjustQttForCanceledOrder::dispatch($this);

        // Refund into wallet if money goes to admin and wallet is loaded
        if (!vendor_get_paid_directly() && $this->isPaid() && customer_has_wallet()) {
            $amount = $this->grand_total;

            if ($partial) {
                $amount = DB::table('order_items')->where('order_id', $this->id)
                    ->whereIn('inventory_id', $this->cancellation->items)
                    ->select(DB::raw('quantity * unit_price AS total'))
                    ->get()->sum('total');
            }

            $cancellation_fee = $cancellation_fee ?? config('system_settings.vendor_order_cancellation_fee');

            $this->refundToWallet($amount, $cancellation_fee);
        }

        if ($partial) {
            event(new OrderCancellationRequestApproved($this));
        } else {
            // Update order status
            $this->order_status_id = static::STATUS_CANCELED;
            $this->save();

            event(new OrderCancelled($this));
        }
    }

    /**
     * Mark the order as paid
     *
     * @return self
     */
    public function markAsPaid()
    {
        $this->payment_status = static::PAYMENT_STATUS_PAID;

        if ($this->order_status_id < static::STATUS_CONFIRMED) {
            $this->order_status_id = static::STATUS_CONFIRMED;
        }

        $this->save();

        if (!vendor_get_paid_directly() && is_incevio_package_loaded('wallet')) {
            $fee = getPlatformFeeForOrder($this);

            // Deposit the order amount into vendor's wallet
            $meta = [
                'type' => trans('app.sale'),
                'description' => trans('app.for_sale_of', ['order' => $this->order_number]),
                'fee' => $fee,
                'order_id' => $this->id,
            ];

            $this->shop->deposit($this->grand_total - $fee, $meta, true);
        }

        // Update shop's periodic sold amount
        if ($this->shop->periodic_sold_amount) {
            $this->shop->periodic_sold_amount += $this->total;
            $this->shop->save();
        }

        event(new OrderPaid($this));

        return $this;
    }

    /**
     * Mark the order as unpaid
     *
     * @return self
     */
    public function markAsUnpaid()
    {
        $this->payment_status = static::PAYMENT_STATUS_UNPAID;

        if ($this->order_status_id == static::STATUS_CONFIRMED) {
            $this->order_status_id = static::STATUS_WAITING_FOR_PAYMENT;
        }

        $this->save();

        if (!vendor_get_paid_directly()) {
            $fee = getPlatformFeeForOrder($this);

            // Deposit the order amount into vendor's wallet
            $meta = [
                'type' => trans('app.reversal'),
                'description' => trans('app.reversal_for_sale_of', ['order' => $this->order_number]),
                'fee' => $fee,
                'order_id' => $this->id,
            ];

            $this->shop->withdraw($this->grand_total - $fee, $meta, true);
        }

        event(new OrderUpdated($this));

        return $this;
    }

    /**
     * Refund the cancellation value to the customers wallet
     *
     * @return void
     */
    private function refundToWallet($amount, $cancellation_fee)
    {
        if (!$this->isPaid()) {
            throw new \Exception(trans('exception.order_not_paid_yet'));
        }

        if (!customer_has_wallet()) {
            throw new \Exception(trans('exception.customer_wallet_not_enabled'));
        }

        $refund = new \Incevio\Package\Wallet\Services\RefundToWallet();

        $refund->sender($this->shop)
            ->receiver($this->customer)
            ->amount($amount)
            ->meta([
                'type' => trans('wallet::lang.refund'),
                'description' => trans('wallet::lang.refund_of', ['order' => $this->order_number]),
            ])
            ->forceTransfer()
            ->execute();

        // Charge the cancellation fee
        if ($cancellation_fee && $cancellation_fee > 0) {
            $meta = [
                'type' => trans('app.cancellation_fee'),
                'description' => trans('app.cancellation_fee'),
            ];

            $this->shop->forceWithdraw($cancellation_fee, $meta);
        }

        // Update payment status
        $this->payment_status = $amount < $this->grand_total ?
            static::PAYMENT_STATUS_PARTIALLY_REFUNDED :
            static::PAYMENT_STATUS_REFUNDED;
        $this->save();
    }

    /**
     * Get Manual Payement Instructions for the order
     */
    public function menualPaymentInstructions()
    {
        if ($this->paymentMethod->type == PaymentMethod::TYPE_MANUAL) {
            if (vendor_get_paid_directly()) {
                $config = \DB::table('config_manual_payments')
                    ->where('shop_id', $this->shop_id)
                    ->where('payment_method_id', $this->payment_method_id)
                    ->select('payment_instructions')->first();

                return $config ? $config->payment_instructions : null;
            }

            return get_from_option_table('wallet_payment_instructions_' . $this->paymentMethod->code);
        }

        return null;
    }

    /**
     * [orderStatus description]
     *
     * @param  bool $plain [description]
     *
     * @return [type]         [description]
     */
    public function orderStatus($plain = false)
    {
        $order_status = strtoupper(get_order_status_name($this->order_status_id));

        if ($plain) {
            return $order_status;
        }

        switch ($this->order_status_id) {
            case static::STATUS_WAITING_FOR_PAYMENT:
            case static::STATUS_PAYMENT_ERROR:
            case static::STATUS_CANCELED:
            case static::STATUS_RETURNED:
                return '<span class="label label-danger">' . $order_status . '</span>';

            case static::STATUS_CONFIRMED:
            case static::STATUS_AWAITING_DELIVERY:
                return '<span class="label label-outline">' . $order_status . '</span>';

            case static::STATUS_FULFILLED:
                return '<span class="label label-info">' . $order_status . '</span>';

            case static::STATUS_DELIVERED:
                return '<span class="label label-primary">' . $order_status . '</span>';
        }

        return null;
    }

    /**
     * [paymentStatusName description]
     *
     * @param  bool $plain [description]
     *
     * @return [type]         [description]
     */
    public function paymentStatusName($plain = false)
    {
        $payment_status = strtoupper(get_payment_status_name($this->payment_status));

        if ($plain) {
            return $payment_status;
        }

        switch ($this->payment_status) {
            case static::PAYMENT_STATUS_UNPAID:
            case static::PAYMENT_STATUS_REFUNDED:
            case static::PAYMENT_STATUS_PARTIALLY_REFUNDED:
                return '<span class="label label-danger">' . $payment_status . '</span>';

            case static::PAYMENT_STATUS_PENDING:
            case static::PAYMENT_STATUS_INITIATED_REFUND:
                return '<span class="label label-info">' . $payment_status . '</span>';

            case static::PAYMENT_STATUS_PAID:
                return '<span class="label label-outline">' . $payment_status . '</span>';
        }

        return null;
    }

    /**return fulfilment type deliver orders*/
    public function deliver()
    {
        return $this->fulfilment_type == self::FULFILMENT_TYPE_DELIVER;
    }

    /**return fulfilment type pickup orders*/
    public function pickup()
    {
        return $this->fulfilment_type == self::FULFILMENT_TYPE_PICKUP;
    }

    /**
     * Return order status
     *
     * @param  int $int [value]
     *
     * @return   [order status]
     */

    // public static function getOrderStatus($status)
    // {
    //     switch ($status) {
    //     case static::STATUS_DELIVERED:
    //         return static::STATUS_DELIVERED;
    //         break;
    //     case static::STATUS_RETURNED:
    //         return static::STATUS_RETURNED;
    //         break;
    //     case static::STATUS_CANCELED:
    //         return static::STATUS_CANCELED;
    //         break;
    //     case static::STATUS_DISPUTED:
    //         return static::STATUS_DISPUTED;
    //         break;
    //     default:
    //         return NULL;
    //     }
    // }
}
