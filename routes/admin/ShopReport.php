<?php

use App\Http\Controllers\Admin\Report\ShopPerformanceIndicatorsController;
use Illuminate\Support\Facades\Route;

//Metrics / Key Performance Indicators...
Route::get('shop/report/kpi', [ShopPerformanceIndicatorsController::class, 'all'])->name('shop-kpi');

Route::get('shop/report/kpi/revenue', [ShopPerformanceIndicatorsController::class, 'revenue'])->name('shop-kpi.revenue');
