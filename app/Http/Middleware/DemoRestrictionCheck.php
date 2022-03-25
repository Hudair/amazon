<?php

namespace App\Http\Middleware;

use Closure;

class DemoRestrictionCheck
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
        if (config('app.demo') == true) {
            return $request->ajax() ?
                response(['message' => trans('messages.demo_restriction')], 444) :
                back()->with('warning', trans('messages.demo_restriction'));
        }

        return $next($request);
    }
}
