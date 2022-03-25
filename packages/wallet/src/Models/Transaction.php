<?php

namespace Incevio\Package\Wallet\Models;

use App\Customer;
use App\Language;
use App\Services\PdfInvoice;
use App\Services\TpdfInvoice;
use App\Shop;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;
use function array_merge;
use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Wallet as WalletModel;
use Incevio\Package\Wallet\Services\WalletService;
use Incevio\Package\Wallet\Services\CommonService;
use function config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    public const TYPE_DEPOSIT   = 'deposit';
    public const TYPE_WITHDRAW  = 'withdraw';
    public const TYPE_REFUND    = 'refund';
    public const TYPE_PAYOUT    = 'payout';

    /**
     * @var array
     */
    protected $fillable = [
        'payable_type',
        'payable_id',
        'wallet_id',
        'uuid',
        'type',
        'amount',
        'balance',
        'confirmed',
        'approved',
        'meta',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'wallet_id' => 'int',
        'confirmed' => 'bool',
        'meta' => 'json',
    ];

    /**
     * {@inheritdoc}
     */
    public function getCasts(): array
    {
        return array_merge(
            parent::getCasts(),
            config('wallet.transaction.casts', [])
        );
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if (! $this->table) {
            $this->table = config('wallet.transaction.table', 'transactions');
        }

        return parent::getTable();
    }

    /**
     * @return MorphTo
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(config('wallet.wallet.model', WalletModel::class));
    }

    /**
     * @return int|float
     */
    public function getAmountFloatAttribute()
    {
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this->wallet);

        return app(Mathable::class)->div($this->amount, $decimalPlaces);
    }

    /**
     * @return unique ID from UUID
     */
    public function getUniqueIdAttribute()
    {
        return explode('-', $this->uuid)[0];
    }

    /**
     * @param int|float $amount
     * @return void
     */
    public function setAmountFloatAttribute($amount): void
    {
        $math = app(Mathable::class);

        $decimalPlaces = app(WalletService::class)->decimalPlaces($this->wallet);

        $this->amount = $math->round($math->mul($amount, $decimalPlaces));
    }

    /**
     * Scope a query to only include withdraw transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdraw');
    }

    /**
    *Return Type diposit
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    /**
     * Scope a query to only include confirmed transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }

    /**
     * Scope a query to only include payout transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePayouts($query)
    {
        return $query->withdrawals();
    }

    /**
     * Scope a query to only include completed payout transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplete($query)
    {
        return $query->where('approved', 1)->orWhereNull('approved');
    }

    /**
     * Scope a query to only include pending transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('approved', 0);
    }

    /**
     * Scope a query to only include declined transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeclined($query)
    {
        return $query->whereNull('approved');
    }

    /**
     * Check if the transaction is type of given type
     *
     * @param  str  $type
     * @return bool
     */
    public function isTypeOf($type)
    {
        return isset($this->meta['type']) && $this->meta['type'] == $type;
    }

    /**
     * Approve the pending payout transactions.
     */
    public function approve($fee = Null)
    {
        $meta['description'] = trans('wallet::lang.payout_approved');
        if ($fee && $fee > 0) {
            $meta['fee'] = $fee;
            $this->amount = ($this->amount + (-$fee));
        }

        $amount = $this->amount * -1;
        app(CommonService::class)->verifyWithdraw($this->wallet, $amount);

        $this->meta = array_merge($this->meta, $meta);
        $this->confirmed = true;
        $this->approved = true;

        DB::transaction(function() use ($amount){
            // Charge the fee on wallet
            $this->wallet->decrement('balance', $amount);

            // Update the transection
            $this->balance = $this->wallet->balance;
            $this->save();
        });
    }

    /**
     * decline the pending payout transactions.
     */
    public function decline()
    {
        $meta['description'] = trans('wallet::lang.payout_declined');

        $this->meta = array_merge($this->meta, $meta);
        $this->confirmed = false;
        $this->approved = Null;
        $this->save();
    }

    public function isApproved()
    {
        return $this->approved === 1 && $this->confirmed == 1;
    }

    public function isDeclined()
    {
        return $this->approved === Null;
    }

    /**
     * Return transaction status text.
     */
    public function statusName($plain = false)
    {
        if ($this->isApproved()) {
            if ($plain) {
                return strtoupper(trans('wallet::lang.approved'));
            }

            return '<span class="label label-outline">' . strtoupper(trans('wallet::lang.approved')) . '</span>';
        }

        if ($this->isDeclined()) {
            if ($plain) {
                return strtoupper(trans('wallet::lang.declined'));
            }

            return '<span class="label label-danger">' . strtoupper(trans('wallet::lang.declined')) . '</span>';
        }

        if ($plain) {
            return strtoupper(trans('wallet::lang.pending'));
        }

        return '<span class="label label-info">' . strtoupper(trans('wallet::lang.pending')) . '</span>';
    }

    /**
     * Returns meta data from meta
     */
    public function getFromMetaData($attr)
    {
        return is_array($this->meta) && array_key_exists($attr, $this->meta) ? $this->meta[$attr] : '';
    }

    /**
     * Get the transaction invoice.
     */
    public function invoice($des = 'D')
    {
        // Temporary solution
        $local = App::getLocale(); // Get current local
        App::setLocale('en'); // Set local to en

        $invoiceFrom = null;
        $invoiceTo = null;

        if(!empty($this->meta['from'])){
            //set Billing From
            $shop = Shop::where('email', $this->meta['from'])->first();
            $invoiceFrom = $shop->primaryAddress->toArray();
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $shop->getName());

            //Set Billing To:
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceTo = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceTo['address_type']);
            $invoiceTo = array_values($invoiceTo);
            array_unshift($invoiceTo, $this->payable->getName());
        }
        elseif(!empty($this->meta['to'])){
            //Set Billing to:
            $shop = Shop::where('email', $this->meta['to'])->first();
            $invoiceTo = $shop->primaryAddress->toArray();
            unset($invoiceTo['address_type']);
            $invoiceTo = array_values($invoiceTo);
            array_unshift($invoiceTo, $shop->getName());

            //Set Billi From
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceFrom = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $this->payable->getName());
        }
        else{
            //Set Billing to:
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceFrom = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $this->payable->getName());

            //Set Invoice To Address:
            $invoiceTo = multi_tag_explode([",", "<br/>"], strip_tags(get_platform_address(),"<br>"));
            $invoiceTo = array_filter(array_map('trim', $invoiceTo));
            array_unshift($invoiceTo,  get_platform_title());
            unset($invoiceTo[1]);
            $invoiceTo = array_values($invoiceTo);

        }

        $title = config('invoice.title') ?? trans("invoice.invoice");


        $invoice =  new PdfInvoice();

        //Create Pdf Class:
        $invoice->setColor(config('invoice.color', '#007fff'));      // pdf color scheme
        $invoice->setDocumentSize(config('invoice.size', 'A4'));      // set document size
        $invoice->setType($title);    // Invoice Type

        //Set logo image if exist
        $logo = get_storage_file_url(optional($this->payable->image)->path, Null);

        if(App::environment('production') && Storage::exists(optional($this->payable->image)->path)) {
            $invoice->setLogo($logo);
        }
       // $invoice->setReference($this->uuid);   // Reference
        $invoice->setDate($this->created_at->format('M d, Y'));   //Billing Date
        $invoice->setTime($this->created_at->format('h:i:s A'));   //Billing Time
        // $invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        //Alter Billing To And Billing From
        $invoice->setFrom($invoiceTo);
        $invoice->setTo($invoiceFrom);

        // Prepare the amounts
        $basicAmount = $this->amount;
        if (isset($this->meta['fee']) && $this->meta['fee'] > 0) {
            $fee = -$this->meta['fee'];
            $basicAmount = $basicAmount - $fee;
        }

        // Set main transection
        $invoice->addItem('', trim($this->meta['description']), 1, $basicAmount);

        // Set fee transection
        if (isset($fee)) {
            $invoice->addItem('', trans('wallet::lang.payout_fee'), 1, $fee);
        }

        //Adding Total
        $invoice->addSummary(trans('invoice.grand_total'), $this->amount, true);

        //Signature
        $stamp = get_invoice_stamp();
        $invoice->setSignature($stamp, 300, 300, 20, 190);

        //Adding signature
        if(config('invoice.company_info_position') == 'right'){
            $invoice->flipflop();
        }

        $invoice->setFooternote(get_platform_title() . " | " . url('/') . " | " .trans('invoice.footer_note'));
        $invoice->render(get_platform_title() . '-' . $this->unique_id .'.pdf', $des);

        // Temporary!
        App::setLocale($local); //Set local to the curret local

        return;
    }


    /**
     * Get the transaction invoice.
     */
    public function customerInvoice($des = 'D')
    {
        // Temporary solution
        $local = App::getLocale(); // Get current local
        App::setLocale('en'); // Set local to en

        $invoiceFrom = null;
        $invoiceTo = null;

        if(!empty($this->meta['from'])){
            //set Billing From
            $customer = Customer::where('email', $this->meta['from'])->first();
            $invoiceFrom = $customer->primaryAddress->toArray();
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $customer->getName());

            //Set Billing To:
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceTo = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceTo['address_type']);
            $invoiceTo = array_values($invoiceTo);
            array_unshift($invoiceTo, $this->payable->getName());
        }
        elseif(!empty($this->meta['to'])){
            //Set Billing to:
            $customer = Customer::where('email', $this->meta['to'])->first();
            $invoiceTo = $customer->primaryAddress->toArray();
            unset($invoiceTo['address_type']);
            $invoiceTo = array_values($invoiceTo);
            array_unshift($invoiceTo, $customer->getName());

            //Set Billi From
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceFrom = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $this->payable->getName());
        }
        else{
            //Set Billing to:
            //Set Invoice To Address:
            $invoiceTo = multi_tag_explode([",", "<br/>"], strip_tags(get_platform_address(),"<br>"));
            $invoiceTo = array_filter(array_map('trim', $invoiceTo));
            array_unshift($invoiceTo,  get_platform_title());
            unset($invoiceTo[1]);
            $invoiceTo = array_values($invoiceTo);

            //Set Billi From
            $vendorAddress = $this->payable->primaryAddress ?? $this->payable->address;
            $invoiceFrom = $vendorAddress ? $vendorAddress->toArray() : [];
            unset($invoiceFrom['address_type']);
            $invoiceFrom = array_values($invoiceFrom);
            array_unshift($invoiceFrom, $this->payable->getName());
        }

        $title = config('invoice.title') ?? trans("invoice.invoice");

        //Create Pdf Class:
        $invoice = new PdfInvoice();

        $invoice->setColor(config('invoice.color', '#007fff'));      // pdf color scheme
        $invoice->setDocumentSize(config('invoice.size', 'A4'));      // set document size
        $invoice->setType($title);    // Invoice Type

        //Set logo image if exist
        $logo = get_storage_file_url(optional($this->payable->image)->path, Null);

        if(App::environment('production') && Storage::exists(optional($this->payable->image)->path)) {
            $invoice->setLogo($logo);
        }
       // $invoice->setReference($this->uuid);   // Reference
        $invoice->setDate($this->created_at->format('M d, Y'));   //Billing Date
        $invoice->setTime($this->created_at->format('h:i:s A'));   //Billing Time
        // $invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
        //Alter From and To Billing address:
        $invoice->setFrom($invoiceTo);
        $invoice->setTo($invoiceFrom);

        // Prepare the amounts
        $basicAmount = $this->amount;
        if (isset($this->meta['fee']) && $this->meta['fee'] > 0) {
            $fee = -$this->meta['fee'];
            $basicAmount = $basicAmount - $fee;
        }

        // Set main transection
        $invoice->addItem('', trim($this->meta['description']), 1, $basicAmount);

        // Set fee transection
        if (isset($fee)) {
            $invoice->addItem('', trans('wallet::lang.payout_fee'), 1, $fee);
        }

        //Adding Total
        $invoice->addSummary(trans('invoice.grand_total'), $this->amount, true);

        //Signature
        $stamp = get_invoice_stamp();
        $invoice->setSignature($stamp, 300, 300, 20, 200);

        //Adding signature
        if(config('invoice.company_info_position') == 'right'){
            $invoice->flipflop();
        }

        $invoice->setFooternote(get_platform_title() . " | " . url('/') . " | " .trans('invoice.footer_note'));
        $invoice->render(get_platform_title() . '-' . $this->unique_id .'.pdf', $des);

        // Temporary!
        App::setLocale($local); //Set local to the curret local

        return;
    }



}
