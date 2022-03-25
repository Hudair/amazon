<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\View\FileViewFinder;
use View;

class SellingTheme
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
        $paths = [
            selling_theme_views_path(),
            selling_theme_views_path('default'),
        ];

        // Reset views path to load theme views
        View::setFinder(new FileViewFinder(app('files'), $paths));
        // View::setFinder(new FileViewFinder(app()['files'], $paths));

        return $next($request);
    }
}
