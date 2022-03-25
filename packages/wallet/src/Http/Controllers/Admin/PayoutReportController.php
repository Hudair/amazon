<?php

namespace Incevio\Package\Wallet\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Incevio\Package\wallet\Repositories\Payouts\EloquentPayoutsRepository;

class PayoutReportController extends Controller
{
    protected $reports;

    public function __construct(EloquentPayoutsRepository $reports)
    {
        $this->reports = $reports;
    }

    /**
     * Payout Report
     * */
    public function report()
    {
        $data = $this->reports->payouts();
        $chartData = $this->reports->chartPayouts();

        $chartDataArray = json_decode(json_encode($chartData), true);

        return view('wallet::admin.reports.report', compact('data', 'chartDataArray'));
    }

    /**
     * Get More Data Via Ajax
     * */
    public function reportGetMore(Request $request)
    {
        $data = $this->reports->morePayouts(self::getPacket($request));

        return response()->json(['data' => $data ]);
    }

    /**
     * Get More Data For Chart Via Ajax
     * */
    public function reportGetMoreForChart(Request $request)
    {
        $data = $this->reports->moreChartPayouts(self::getPacket($request));

        return response()->json(['data' => $data ]);
    }

    /**
     * Get Array From Request For Search:
     * */
    public function getPacket($request) : array
    {
       return [
           'fromDate' => Carbon::createFromDate($request->get('fromDate')),
           'toDate' => Carbon::createFromDate($request->get('toDate')),
           'payoutType' => $request->get('payoutType'),
           'status' => $request->get('status')
        ];
    }
}
