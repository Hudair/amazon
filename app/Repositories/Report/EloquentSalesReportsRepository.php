<?php

namespace App\Repositories\Report;

use App\Models\Order;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Repositories\Report\SalesReportsRepository as Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class EloquentSalesReportsRepository extends EloquentRepository implements BaseRepository, Contract
{
    private $orders = 'orders';
    private $customers = 'customers';
    private $paymentMethod = 'payment_methods';
    private $shops = 'shops';
    private $products = 'products';
    private $orderItems = 'order_items';
    private $inventories = 'inventories';

    //#Get status values from Order model:
    public function getStatusFromOrder($status)
    {
        $order = new ReflectionClass(\App\Models\Order::class);

        return ! empty($status) ? $order->getConstant($status) : 0;
    }

    //#get Days Diff Form Date
    public function getDaysFromDate($fromDate, $toDate)
    {
        $diff = date_diff($fromDate, $toDate);

        return $diff->days;
    }

    /**
     *Orders all Data default show 7 days data:
     */
    public function orders($date = null)
    {
        return self::commonDataQuery()
        ->whereDate($this->orders.'.created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
        ->get();
    }

    //#orders data search by from Date and to Date:
    public function orderSearch(Carbon $fromDate, Carbon $toDate, array $packet)
    {
        $common = self::commonDataQuery();
        $data = self::commonSearchQuery($common, $packet);
        $data = $data->whereBetween($this->orders.'.created_at', [$fromDate, $toDate]);

        return $data->get();
    }

    //all Chart Data default 7 days:
    public function orderChart($date = null)
    {
        return self::commonChartQuery(7)
        ->whereDate($this->orders.'.created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
        ->get();
    }

    //Date to Date Data For Chart:
    public function orderChartSearch(Carbon $fromDate, Carbon $toDate, array $packet)
    {
        $common = self::commonChartQuery(self::getDaysFromDate($fromDate, $toDate));
        $data = self::commonSearchQuery($common, $packet);
        $data = $data->whereBetween($this->orders.'.created_at', [$fromDate, $toDate]);

        return $data->get();
    }

    //#Common Query For All Sales Report
    public function commonDataQuery()
    {
        return  DB::table($this->orders)
            ->leftJoin($this->customers, $this->customers.'.id', $this->orders.'.customer_id')
            ->leftJoin($this->shops, $this->shops.'.id', $this->orders.'.shop_id')
            ->select(
                $this->orders.'.id',
                $this->orders.'.order_number',
                DB::raw('DATE('.$this->orders.'.created_at) as date'),
                $this->orders.'.total',
                $this->shops.'.name as shop',
                $this->orders.'.quantity',
                $this->orders.'.grand_total',
                $this->orders.'.delivery_date',
                $this->customers.'.name as customer'
            );
    }

    //#Common Query For Chart:
    public function commonChartQuery($days)
    {
        $query = DB::table($this->orders)->select(
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_AWAITING_DELIVERY.' THEN COUNT(*) ELSE 0 END)  as awaiting_delivery'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_WAITING_FOR_PAYMENT.' THEN COUNT(*) ELSE 0 END)  as awaiting_payment'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_CANCELED.' THEN COUNT(*) ELSE 0 END)  as canceled'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_CONFIRMED.' THEN COUNT(*) ELSE 0 END)  as confirmed'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_DELIVERED.' THEN COUNT(*) ELSE 0 END)  as delivered'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_FULFILLED.' THEN COUNT(*) ELSE 0 END)  as fulfilled'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_PAYMENT_ERROR.' THEN COUNT(*) ELSE 0 END)  as payment_error'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_RETURNED.' THEN COUNT(*) ELSE 0 END)  as returned'),
            DB::raw('(CASE WHEN order_status_id = '.Order::STATUS_DISPUTED.' THEN COUNT(*) ELSE 0 END)  as disputed')
        );

        if ($days > 30) {
            $query->addSelect(DB::raw('DATE_FORMAT('.$this->orders.'.created_at, "%Y-%m") as date'));
        } else {
            $query->addSelect(DB::raw('DATE('.$this->orders.'.created_at) as date'));
        }

        return $query->groupBy('date', 'order_status_id');
    }

    //#Common Query For Search By Customer, Shop, Order number and Order Status
    public function commonSearchQuery($data, array $packet)
    {
        $statusId = self::getStatusFromOrder($packet['order_status'] ?? null);
        $paymentStatus = self::getStatusFromOrder($packet['payment_status'] ?? null);

        if ($statusId !== 0) {
            $data = $data->where($this->orders.'.order_status_id', $statusId);
        }

        if ($paymentStatus !== 0) {
            $data = $data->where($this->orders.'.payment_status', $paymentStatus);
        }

        if (! empty($packet['customer_id'])) {
            $data = $data->where($this->orders.'.customer_id', (int) $packet['customer_id']);
        }

        if (! empty($packet['shop_id'])) {
            $data = $data->where($this->orders.'.shop_id', (int) $packet['shop_id']);
        }

        if (! empty($packet['order_number'])) {
            $data = $data->where($this->orders.'.order_number', $packet['order_number']);
        }

        if (! empty($packet['payment_method'])) {
            $data = $data->where($this->orders.'.payment_method_id', $packet['payment_method']);
        }

        return $data;
    }

    /**
     ** End Orders Report Functions
     *
     *
     * Start Of Payments Report Functions
     **/

    //#Payment Chart
    public function paymentChart($date = null)
    {
        return self::paymentChartCommonQuery(7)
        ->whereDate('created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
        ->get();
    }

    //#Payment Methods
    public function paymentChartByPaymentMethod($date = null)
    {
        try {
            return  self::commonQueryByPaymentMethods()
            ->whereDate($this->orders.'.created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
            ->get();
        } catch (\Exception $exception) {
            return get_exception_message($exception);
        }
    }

    //#Payment Status
    public function paymentChartByPaymentStatus($date = null)
    {
        try {
            return self::commonQueryByPaymentStatus()
            ->whereDate('created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
            ->get();
        } catch (\Exception $exception) {
            return get_exception_message($exception);
        }
    }

    //# payment data search by from date and to date:
    public function getMoreByMethod(Carbon $fromDate, Carbon $toDate, $packet)
    {
        $data = self::commonQueryByPaymentMethods();
        $data = self::commonSearchQuery($data, $packet);
        $data->whereBetween($this->orders.'.created_at', [$fromDate, $toDate]);

        return $data->get();
    }

    //# payment data search by from date and to date:
    public function getMoreByStatus(Carbon $fromDate, Carbon $toDate, $packet)
    {
        $data = self::commonQueryByPaymentStatus();
        $searchUnpaid = self::commonSearchQuery($data, $packet);
        $result = $searchUnpaid->whereBetween(DB::raw('created_at'), [$fromDate, $toDate]);

        return $result->get();
    }

    //#Payment Chart data search by from Date and to Date:
    public function paymentChartSearch(Carbon $fromDate, Carbon $toDate, $packet)
    {
        $data = self::paymentChartCommonQuery(self::getDaysFromDate($fromDate, $toDate));
        $data = self::commonSearchQuery($data, $packet);
        $data = $data->whereBetween($this->orders.'.created_at', [$fromDate, $toDate]);

        return $data->get();
    }

    //#Common Query For Payment Chart Query:
    public function paymentChartCommonQuery($days)
    {
        $query = DB::table($this->orders)->select(
           DB::raw(
           'CASE payment_status
                    WHEN '.Order::PAYMENT_STATUS_UNPAID.' THEN SUM(grand_total)
                    WHEN '.Order::PAYMENT_STATUS_PENDING.' THEN SUM(grand_total)
                    ELSE 0
                    END AS pending'
           ),
           DB::raw(
           'CASE payment_status
                    WHEN  '.Order::PAYMENT_STATUS_PARTIALLY_REFUNDED.' THEN SUM(grand_total)
                    WHEN  '.Order::PAYMENT_STATUS_REFUNDED.' THEN SUM(grand_total)
                    ELSE 0
                    END AS refunded'
           ),
           DB::raw(
           'CASE payment_status
                    WHEN '.Order::PAYMENT_STATUS_PAID.' THEN SUM(grand_total)
                    WHEN '.Order::PAYMENT_STATUS_INITIATED_REFUND.' THEN SUM(grand_total)
                    ELSE 0
                    END AS paid'
           )
        );

        if ($days > 30) {
            $query = $query->addSelect(DB::raw('DATE_FORMAT('.$this->orders.'.created_at, "%Y-%m") as date'));
        } else {
            $query = $query->addSelect(DB::raw('DATE_FORMAT('.$this->orders.'.created_at, "%Y-%m-%d %H:%i") as date'));
        }

        return $query->groupBy('date', 'payment_status');
    }

    //#Common Query For Payment Chart By Payment Status:
    public function commonQueryByPaymentStatus()
    {
        try {
            return DB::table($this->orders)
                ->select(
                    DB::raw(
                        'CASE payment_status
                        WHEN '.Order::PAYMENT_STATUS_UNPAID.' THEN SUM(grand_total)
                        WHEN '.Order::PAYMENT_STATUS_PENDING.' THEN SUM(grand_total)
                        ELSE 0
                        END AS pending'
                    ),
                    DB::raw(
                        'CASE payment_status
                        WHEN  '.Order::PAYMENT_STATUS_PARTIALLY_REFUNDED.' THEN SUM(grand_total)
                        WHEN  '.Order::PAYMENT_STATUS_REFUNDED.' THEN SUM(grand_total)
                        ELSE 0
                        END AS refunded'
                    ),
                    DB::raw(
                        'CASE payment_status
                        WHEN '.Order::PAYMENT_STATUS_PAID.' THEN SUM(grand_total)
                        WHEN '.Order::PAYMENT_STATUS_INITIATED_REFUND.' THEN SUM(grand_total)
                        ELSE 0
                        END AS paid'
                    )
                )
                ->groupBy('payment_status')
                ->orderBy('payment_status', 'asc');
        } catch (\Exception $exception) {
            return get_exception_message($exception);
        }
    }

    //#Common Query For Payment Chart By Payment Methods:
    public function commonQueryByPaymentMethods()
    {
        try {
            return DB::table($this->paymentMethod)
                ->leftJoin($this->orders, $this->orders.'.payment_method_id', $this->paymentMethod.'.id')
                ->select(
                    $this->paymentMethod.'.name',
                    DB::raw('SUM('.$this->orders.'.grand_total) as total')
                )
            ->groupBy($this->orders.'.payment_method_id');
        } catch (\Exception $exception) {
            return get_exception_message($exception);
        }
    }

    /**
     ** End Payments Report Functions
     *
     *
     * Start Of Products Report Functions
     **/

    //#Products Data :
    public function products($date = null)
    {
        return self::productsCommonQuery()
            ->whereDate($this->orders.'.created_at', '>', $date ?? Carbon::today()->subDays(config('report.sales.default', 7)))
            ->get();
    }

    //#Product search
    public function productsSearch(Carbon $fromDate, Carbon $toDate, array $packet)
    {
        $data = self::productsCommonQuery();
        $data = $data->whereBetween($this->orders.'.created_at', [$fromDate, $toDate]);

        if (! empty($packet['product_id'])) {
            $data = $data->where($this->products.'.id', (int) $packet['product_id']);
        }

        if (! empty($packet['shop_id'])) {
            $data = $data->where($this->orders.'.shop_id', (int) $packet['shop_id']);
        }

        return $data->get();
    }

    //#payment Common Query For Data Joining:
    public function productsCommonQuery()
    {
        return DB::table($this->orderItems)
            ->leftJoin($this->inventories, $this->inventories.'.id', $this->orderItems.'.inventory_id')
            ->leftJoin($this->orders, $this->orders.'.id', $this->orderItems.'.order_id')
            ->leftJoin($this->products, $this->products.'.id', $this->inventories.'.product_id')
            ->select(
                DB::raw('COUNT('.$this->orderItems.'.order_id) as uniquePurchase'),
                DB::raw('AVG('.$this->orders.'.total) as avgPrice'),
                DB::raw('SUM('.$this->orderItems.'.quantity) as quantity'),
                $this->products.'.name',
                $this->products.'.model_number',
                $this->products.'.gtin',
                $this->products.'.gtin_type',
                DB::raw('SUM('.$this->orders.'.grand_total) as totalSale')
            )
            ->groupBy($this->products.'.id');
    }
}
