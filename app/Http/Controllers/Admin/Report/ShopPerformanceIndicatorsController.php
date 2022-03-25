<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;

class ShopPerformanceIndicatorsController extends Controller
{
    /**
     * Get the performance indicators for the application.
     *
     * @return Response
     */
    public function all()
    {
        return view('admin.report.merchant.kpi');
    }
}
