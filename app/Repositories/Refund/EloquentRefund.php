<?php

namespace App\Repositories\Refund;

use App\Models\Order;
use App\Models\Refund;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;


class EloquentRefund extends EloquentRepository implements BaseRepository, RefundRepository
{
    protected $model;

    public function __construct(Refund $refund)
    {
        $this->model = $refund;
    }

    public function all()
    {
        $query = $this->model->with('order');

        if (Auth::user()->isFromPlatform()) {
            return $query->get();
        }

        return $query->mine()->get();
    }

    public function open()
    {
        $query = $this->model->open()->with('order');

        if (Auth::user()->isFromPlatform()) {
            return $query->get();
        }

        return $query->mine()->get();
    }

    public function closed()
    {
        $query = $this->model->closed()->with('order');

        if (Auth::user()->isFromPlatform()) {
            return $query->get();
        }

        return $query->mine()->get();
    }

    public function statusOf($status)
    {
        $query = $this->model->statusOf($status)->with('order');

        if (Auth::user()->isFromPlatform()) {
            return $query->get();
        }

        return $query->mine()->get();
    }

    public function approve($refund)
    {
        if (!$refund instanceof Refund) {
            $refund = $this->getInst($refund);
        }

        $refund->update(['status' => Refund::STATUS_APPROVED]);

        return $refund;
    }

    public function decline($refund)
    {
        if (!$refund instanceof Refund) {
            $refund = $this->getInst($refund);
        }

        $refund->update(['status' => Refund::STATUS_DECLINED]);

        return $refund;
    }

    private function getInst($refund)
    {
        return Refund::findOrFail($refund);
    }

    public function findOrder($order)
    {
        return Order::findOrFail($order);
    }
}
