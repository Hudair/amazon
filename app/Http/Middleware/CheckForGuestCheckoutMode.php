<?php

namespace App\Http\Middleware;

use Closure;

class CheckForGuestCheckoutMode
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
        if (!allow_checkout()) {
            return redirect()->route('customer.login')
                ->with('error', trans('theme.notify.please_login_to_checkout'));
        }

        return $next($request);
    }
}
