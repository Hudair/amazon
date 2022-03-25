<?php

use Incevio\Package\Wallet\Models\Transection;
use Illuminate\Support\Str;

if (! function_exists('get_min_withdrawal_limit'))
{
    /**
     * Return min_withdrawal_limit
     */
    function get_min_withdrawal_limit()
    {
        return get_from_option_table('wallet_min_withdrawal_limit', config('wallet.default.min_withdrawal_limit', 100));
    }
}
