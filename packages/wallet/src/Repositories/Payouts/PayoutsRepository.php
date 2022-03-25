<?php

namespace Incevio\Package\wallet\Repositories\Payouts;

use Carbon\Carbon;

interface PayoutsRepository
{

    public function payouts(Carbon $date = null);
    public function chartPayouts(Carbon $date = null);
    public function morePayouts(array $packet);
    public function moreChartPayouts(array $packet);

}