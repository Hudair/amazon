<?php

namespace App\Http\Middleware;

use Closure;

class IsSubscriptionEnabled
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
        if (!is_subscription_enabled()) {
            abort(403, 'Subscription module is not enabled!');
        }

        return $next($request);
    }
}
