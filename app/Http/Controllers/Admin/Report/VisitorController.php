<?php

namespace App\Http\Controllers\Admin\Report;

use App\Models\Visitor;
use App\Models\SystemConfig;
use App\Charts\Visitors;
use App\Charts\Referrers;
use App\Charts\DeviceTypes;
use App\Charts\VisitorTypes;
use App\Helpers\CharttHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class VisitorController extends Controller
{
    /**
     * Get the performance indicators for the application.
     *
     * @return Response
     */
    public function index()
    {
        if (!SystemConfig::isGgoogleAnalyticReady()) {
            return view('admin.report.platform.visitors');
        }

        $months = config('charts.google_analytic.period');

        // DeviceTypes Chart
        $topDevicesData = CharttHelper::topDevice($months);

        $chartDevices = new DeviceTypes($topDevicesData->pluck('deviceCategory'));

        $chartDevices->dataset(trans('app.sessions'), 'pie', $topDevicesData->pluck('sessions')->toArray())->color(['#e4d354', '#7CB5EC']);

        // VisitorTypes Chart
        $visitorTypesData = CharttHelper::userTypes($months);

        $chartVisitorTypes = new VisitorTypes($visitorTypesData->pluck('type'));

        $chartVisitorTypes->dataset(trans('app.sessions'), 'pie', $visitorTypesData->pluck('sessions')->toArray())->color(['#7CB5EC', '#FFBC75']);

        // Referrers Chart
        $referrersData = CharttHelper::topReferrers($months);

        $chartReferrers = new Referrers($referrersData->pluck('url'));

        $chartReferrers->dataset(trans('app.page_views'), 'bar', $referrersData->pluck('pageViews'));

        // Visitors Chart
        $visitorsData = CharttHelper::fetchVisitorsOfMonthsFromGoogle($months);

        $chartVisitors = new Visitors($months);

        $chartVisitors->dataset(trans('app.page_views'), 'column', $visitorsData['page_views'])->color(config('charts.visitors.colors.page_views'));

        $chartVisitors->dataset(trans('app.sessions'), 'column', $visitorsData['sessions'])->color(config('charts.visitors.colors.sessions'));

        $chartVisitors->dataset(trans('app.unique_visits'), 'column', $visitorsData['visits'])->color(config('charts.visitors.colors.unique_visits'));

        return view('admin.report.platform.visitors', compact('chartVisitors', 'chartReferrers', 'chartVisitorTypes', 'chartDevices'));
    }

    public function ban(Request $request, Visitor $visitor)
    {
        $visitor->delete();

        return redirect()->back()->with('success', trans('messages.the_ip_banned'));
    }

    public function unban(Request $request, $id)
    {
        $visitor = Visitor::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->back()->with('success', trans('messages.the_ip_unbanned'));
    }

    // function will process the ajax request
    public function getVisitors(Request $request)
    {
        $visitors = Visitor::orderBy('hits', 'desc')->withTrashed()->get();

        return Datatables::of($visitors)
            ->addColumn('flag', function ($visitor) {
                return view('admin.partials.actions.visitor.flag', compact('visitor'));
            })
            ->addColumn('option', function ($visitor) {
                return view('admin.partials.actions.visitor.options', compact('visitor'));
            })
            ->editColumn('last_visits', function ($visitor) {
                return view('admin.partials.actions.visitor.last_visits', compact('visitor'));
            })
            ->rawColumns(['flag', 'last_visits', 'option'])
            ->make(true);
    }
}
