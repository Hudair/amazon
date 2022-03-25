<?php

namespace App\Models;

use App\Common\Attachable;
use App\Common\Repliable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends BaseModel
{
    use HasFactory, SoftDeletes, Repliable, Attachable;

    const STATUS_NEW = 1;         //Default
    const STATUS_UNREAD = 2;       //All status before UNREAD value consider as unread
    const STATUS_READ = 3;

    const LABEL_INBOX = 1;       //Default
    const LABEL_SENT = 2;
    const LABEL_DRAFT = 3;       //All labels before this DRAFT can be replied and All labels after this DRAFT can be deleted permanently
    const LABEL_SPAM = 4;
    const LABEL_TRASH = 5;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'email',
        'user_id',
        'subject',
        'message',
        'customer_id',
        'order_id',
        'product_id',
        'status',
        'customer_status',
        'label',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    // protected static function boot()
    // {
    //     parent::boot();

    //     if (Auth::check()) {
    //         static::addGlobalScope(new MineScope);
    //     }
    // }

    /**
     * Get the shop associated with the model.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the user associated with the model.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('theme.user'),
        ]);
    }

    /**
     * Get the customer associated with the model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)
            ->withDefault([
                'name' => trans('app.guest_customer'),
            ])
            ->withoutGlobalScope('MineScope');
    }

    /**
     * Get the order associated with the model.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the shop associated with the model.
     */
    public function item()
    {
        return $this->belongsTo(Inventory::class, 'product_id');
    }

    /**
     * Scope a query to only include records that have the given status.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusOf($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include records that have the given label.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLabelOf($query, $label)
    {
        return $query->where('label', $label);
    }

    /**
     * Scope a query to only include records from the user.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyMessages($query)
    {
        return $query->where('customer_id', Auth::id());
    }

    /**
     * Scope a query to only include unread messages.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        $status = $this->getStatusCell();

        return $query->where($status, '<', self::STATUS_READ);
    }

    /**
     * Scope a query to only include spam messages.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpam($query)
    {
        return $query->where('status', self::STATUS_SPAM);
    }

    /**
     * Scope a query to only include non archived messages.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotArchived($query)
    {
        return $query->where([
            ['customer_status', '!=', self::LABEL_TRASH],
            ['label', '!=', self::LABEL_TRASH],
        ]);
    }

    public function about()
    {
        if ($this->order) {
            $str = trans('app.order') . ': ' . $this->order->order_number;
        } elseif ($this->item) {
            $str = trans('app.product') . ': ' . $this->item->sku;
        }

        return isset($str) ? '<span class="label label-outline">' . $str . '</span>' : '';
    }

    /**
     * Check if the message is unread
     * @return bool
     */
    public function isUnread()
    {
        $status = $this->getStatusCell();

        return $this->$status < static::STATUS_READ;
    }

    /**
     * mark the message as unread
     */
    public function markAsUnread()
    {
        $this->forceFill([$this->getStatusCell() => static::STATUS_UNREAD])->save();
    }

    /**
     * mark the message as read
     */
    public function markAsRead()
    {
        $this->forceFill([$this->getStatusCell() => static::STATUS_READ])->save();
    }

    /**
     * Archive the message
     */
    public function archive()
    {
        $this->forceFill(['customer_status' => static::LABEL_TRASH])->save();
    }

    /**
     * Mark the message as unread when replied
     */
    public function hasNewReply()
    {
        $status = $this->getStatusCell();

        if ($status == 'customer_status') {
            $data = [
                'status' => static::STATUS_NEW,
                'label' => static::LABEL_INBOX,
            ];
        } else {
            $data = [
                'customer_status' => static::STATUS_UNREAD,
            ];
        }

        $this->forceFill($data)->save();
    }

    public function labelName()
    {
        switch ($this->label) {
            case static::LABEL_INBOX:
                return '<span class="label label-info">' . trans('app.message_labels.inbox') . '</span>';
            case static::LABEL_SENT:
                return '<span class="label label-outline">' . trans('app.message_labels.sent') . '</span>';
            case static::LABEL_DRAFT:
                return '<span class="label label-default">' . trans('app.message_labels.draft') . '</span>';
            case static::LABEL_SPAM:
                return '<span class="label label-danger">' . trans('app.message_labels.spam') . '</span>';
            case static::LABEL_TRASH:
                return '<span class="label label-warning">' . trans('app.message_labels.trash') . '</span>';
        }
    }

    public function statusName()
    {
        $status = $this->getStatusCell();

        switch ($this->$status) {
            case static::STATUS_NEW:
                return '<span class="label label-primary">' . trans('app.statuses.new') . '</span>';
            case static::STATUS_UNREAD:
                return '<span class="label label-info">' . trans('app.statuses.unread') . '</span>';
            case static::STATUS_READ:
                return '<span class="label label-default">' . trans('app.statuses.read') . '</span>';
        }
    }

    public function getStatusCell()
    {
        return Auth::user() instanceof Customer ? 'customer_status' : 'status';
    }
}
