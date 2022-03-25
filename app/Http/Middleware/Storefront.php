<?php

namespace App\Http\Middleware;

use App\Helpers\ListHelper;
use App\Jobs\UpdateVisitorTable;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class Storefront
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
        // Check platform maintenance_mode
        if (config('system_settings.maintenance_mode')) {
            return response()->view('errors.503', [], 503);
        }

        //Supply important data to all views if not ajax request
        if (!$request->ajax()) {
            // $expires = system_cache_remember_for();

            // View::share('active_announcement', ListHelper::activeAnnouncement());

            if (active_theme() == 'legacy') {
                View::share('featured_categories', get_featured_category());
            }

            View::share('promotional_tagline', get_promotional_tagline());
            View::share('pages', ListHelper::pages(\App\Models\Page::VISIBILITY_PUBLIC));
            View::share('all_categories', ListHelper::categoriesForTheme());
            View::share('search_category_list', ListHelper::search_categories());
            View::share('recently_viewed_items', ListHelper::recentlyViewedItems());
            View::share('cart_item_count', cart_item_count());
            View::share('global_announcement', get_global_announcement());
            // View::share('top_vendors', ListHelper::top_vendors(5));

            // $languages = \App\Language::orderBy('order', 'asc')->active()->get();

            // update the visitor table for state
            $ip = get_visitor_IP();
            UpdateVisitorTable::dispatch($ip);
        }

        return $next($request);
    }
}
