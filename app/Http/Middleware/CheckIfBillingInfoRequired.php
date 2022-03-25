<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfBillingInfoRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            !is_billing_info_required() ||
            $request->user()->isFromPlatform() ||
            $request->user()->hasBillingInfo()
        ) {
            return $next($request);
        }

        return $request->ajax() || $request->wantsJson() ?
            response(trans('messages.no_card_added'), 402)
            : redirect()->route('admin.account.billing')->with('success', trans('messages.no_card_added'));
    }
}
