<?php

use App\Http\Controllers\Admin\Report\PerformanceIndicatorsController;
use App\Http\Controllers\Admin\Report\SalesReportController;
use Illuminate\Support\Facades\Route;

//Metrics / Key Performance Indicators...
Route::get('report/kpi', [PerformanceIndicatorsController::class, 'all'])->name('kpi');

Route::get('report/kpi/revenue', [PerformanceIndicatorsController::class, 'revenue'])->name('kpi.revenue');

Route::get('report/kpi/plans', [PerformanceIndicatorsController::class, 'subscribers'])->name('kpi.plans');

Route::get('report/kpi/trialing', [PerformanceIndicatorsController::class, 'trialUsers'])->name('kpi.trialing');

//Sales report
//Order Wise Report
Route::get('report/sales/orders', [SalesReportController::class, 'orders'])->name('sales.orders');

Route::get('report/sales/getMore', [SalesReportController::class, 'getMoreOrder'])->name('sales.getMore')->middleware('ajax');

Route::get('report/sales/getMoreForChart', [SalesReportController::class, 'getMoreForChart'])->name('sales.getMoreForChart')->middleware('ajax');

//#Paymnet Wise Report
Route::get('report/sales/payments', [SalesReportController::class, 'payments'])->name('sales.payments');

Route::get('report/sales/payments/getMethod', [SalesReportController::class, 'getMoreByMethod'])->name('sales.payments.getMethod');

Route::get('report/sales/payments/getStatus', [SalesReportController::class, 'getMoreByStatus'])->name('sales.payments.getStatus');

Route::get('report/sales/payments/getMoreForChart', [SalesReportController::class, 'getMorePaymentForChart'])->name('sales.payments.getMoreForChart');

//#Product Wise Report
Route::get('report/sales/products', [SalesReportController::class, 'products'])->name('sales.products');

Route::get('report/sales/products/getMore', [SalesReportController::class, 'productsSearch'])->name('sales.products.getMore');
