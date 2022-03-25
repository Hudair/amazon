<?php

namespace App\Repositories\Report;

use Carbon\Carbon;

interface SalesReportsRepository
{
    //#Orders:
    public function orders(Carbon $date);

    public function orderSearch(Carbon $fromDate, Carbon $toDate, array $packet);

    public function orderChart(Carbon $date);

    public function orderChartSearch(Carbon $fromDate, Carbon $toDate, array $packet);

    //#Payments
    public function paymentChart(Carbon $date);

    public function paymentChartByPaymentMethod(Carbon $date);

    public function paymentChartByPaymentStatus(Carbon $date);

    public function paymentChartSearch(Carbon $fromDate, Carbon $toDate, array $packet);

    public function getMoreByMethod(Carbon $fromDate, Carbon $toDate, $packet);

    public function getMoreByStatus(Carbon $fromDate, Carbon $toDate, $packet);

    //#Products
    public function products(Carbon $date);

    public function productsSearch(Carbon $fromDate, Carbon $toDate, array $packet);
}
