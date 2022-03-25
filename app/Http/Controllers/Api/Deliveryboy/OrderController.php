<?php

namespace App\Http\Controllers\Api\Deliveryboy;

use App\Models\Order;
use App\Helpers\ApiAlert;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryBoy\OrderStatusRequest;
use App\Http\Requests\DeliveryBoy\MyDeliveryRequest;
use App\Http\Resources\OrderLightResource;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    use ApiAlert;

    /**
     * Delivery boy orders
     * 
     * @return [orders]
     */
    public function index()
    {
        $orders = Order::toDeliver()->myDelivery()->get();

        return OrderLightResource::collection($orders);
    }

    /**
     * This method will show the order details by id
     * 
     * @param [order_id]
     * 
     * @return [order]
     */
    public function show(MyDeliveryRequest $request, Order $order)
    {
        return new OrderResource($order);
    }

    // /**
    //  * This method will updated the order delivery status
    //  * 
    //  * @param [order_id] [request]
    //  * 
    //  * @return [orders]
    //  */
    // public function updateOrderStatus(OrderStatusRequest $request, Order $order)
    // {
    //     $order->order_status_id = $request->order_status;

    //     if ($order->save()) {
    //         return $this->success(trans('api.order_status_updated'));
    //     }

    //     return $this->error(trans('api.something_Went_wrong'));
    // }

    /**
     * This method will updated the order delivery status
     * 
     * @param [order_id] [request]
     * 
     * @return [orders]
     */
    public function markAsDelivered(MyDeliveryRequest $request, Order $order)
    {
        try {
            $order->mark_as_goods_received();
        } catch (\Exception $e) {
            return $this->error(trans('api.something_Went_wrong'));
        }

        return $this->success(trans('api.order_status_updated'));
    }

    /**
     * This method will updated the order payment status
     * 
     * @param [order_id] [request]
     * 
     * @return [orders]
     */
    public function markAsPaid(MyDeliveryRequest $request, Order $order)
    {
        try {
            $order->markAsPaid();
        } catch (\Exception $e) {
            return $this->error(trans('api.something_Went_wrong'));
        }

        return $this->success(trans('api.order_status_updated'));
    }
}
