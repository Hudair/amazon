<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Repositories\Report\SalesReportsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    //Report is the instance of SalesReportsRepository
    protected $reports;

    /**
     * Create a new SalesReportsRepository instance.
     */
    public function __construct(SalesReportsRepository $reports)
    {
        $this->reports = $reports;
    }

    /**
     * Get all sales data
     */
    public function orders(Request $request)
    {
        $data = $this->reports->orders();
        $chartData = $this->reports->orderChart();
        $chartDataArray = json_decode(json_encode($chartData), true);

        return view('admin.report.platform.sales.orders', compact('data', 'chartDataArray'));
    }

    /**
     *  #Vaia Ajax DataTable Data;
     */
    public function getMoreOrder(Request $request)
    {
        $status = [
            'payment_status' => $request->get('paymentStatus') ?? null,
            'order_status' => $request->get('orderStatus') ?? null,
            'customer_id' => ($request->get('customerId') !== 'null') ? $request->get('customerId') : null,
            'shop_id' => ($request->get('shopId') !== 'null') ? $request->get('shopId') : null,
            'order_number' => ($request->get('orderNumber') !== 'null') ? $request->get('orderNumber') : null,
        ];

        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));

        $salesReport = $this->reports->orderSearch($fromDate, $toDate, $status);

        return response()->json(['data' => $salesReport]);
    }

    /**
     * Vaia Ajax Chart Data:
     */
    public function getMoreForChart(Request $request)
    {
        $status = [
            'payment_status' => $request->get('paymentStatus') ?? null,
            'order_status' => $request->get('orderStatus') ?? null,
            'customer_id' => ($request->get('customerId') !== 'null') ? $request->get('customerId') : null,
            'shop_id' => ($request->get('shopId') !== 'null') ? $request->get('shopId') : null,
            'order_number' => ($request->get('orderNumber') !== 'null') ? $request->get('orderNumber') : null,
        ];

        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));

        $salesReport = $this->reports->orderChartSearch($fromDate, $toDate, $status);

        $chartDataArray = json_decode(json_encode($salesReport), true);

        return response()->json(['data' => $chartDataArray]);
    }

    //#all Payments Data:
    public function payments(Request $request)
    {
        $paymentMethods = PaymentMethod::all();

        $chartData = $this->reports->paymentChart(Carbon::today()->subDays(7));
        $paymentMethod = $this->reports->paymentChartByPaymentMethod(Carbon::today()->subDays(7));
        $paymentStatus = $this->reports->paymentChartByPaymentStatus(Carbon::today()->subDays(7));

        $chartDataArray = json_decode(json_encode($chartData), true);
        $paymentMethod = json_decode(json_encode($paymentMethod), true);
        $paymentStatus = json_decode(json_encode($paymentStatus), true);

        return view('admin.report.platform.sales.payments', compact('chartDataArray',
            'paymentMethods', 'paymentMethod', 'paymentStatus'));
    }

    //#ajax call for date to date payment Data:
    public function getMoreByMethod(Request $request)
    {
        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));
        $packet = [
            'payment_status' => $request->get('paymentStatus'),
            'payment_method' => $request->get('paymentMethod'),
        ];
        $method = $this->reports->getMoreByMethod($fromDate, $toDate, $packet);

        return response()->json(['data' => $method]);
    }

    //#ajax call for date to date payment Data:
    public function getMoreByStatus(Request $request)
    {
        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));
        $packet = [
            'payment_status' => $request->get('paymentStatus'),
            'payment_method' => $request->get('paymentMethod'),
        ];

        $status = $this->reports->getMoreByStatus($fromDate, $toDate, $packet);

        return response()->json(['data' => $status]);
    }

    //ajax For Payment Chart Data
    public function getMorePaymentForChart(Request $request)
    {
        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));

        $packet = [
            'payment_status' => $request->get('paymentStatus'),
            'payment_method' => $request->get('paymentMethod'),
        ];

        $chartData = $this->reports->paymentChartSearch($fromDate, $toDate, $packet);
        $chartDataArray = json_decode(json_encode($chartData), true);

        return response()->json(['data' => $chartDataArray]);
    }

    //#Products :
    public function products(Request $request)
    {
        $data = $this->reports->products(Carbon::today()->subMonths(7));

        return view('admin.report.platform.sales.products', compact('data'));
    }

    //Product Search Function:
    public function productsSearch(Request $request)
    {
        $fromDate = Carbon::createFromDate($request->get('fromDate'));
        $toDate = Carbon::createFromDate($request->get('toDate'));
        $packet = [
            'product_id' => ($request->get('productId') !== 'null') ? $request->get('productId') : null,
            'shop_id' => ($request->get('shopId') !== 'null') ? $request->get('shopId') : null,
        ];

        $data = $this->reports->productsSearch($fromDate, $toDate, $packet);

        return response()->json(['data' => $data]);
    }
}
