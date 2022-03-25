<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Class LocaleMiddleware.
 */
class Language
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** 
         * Locale is enabled and allowed to be changed
         */
        if (Session::has('locale')) {
            $locale = Session::get('locale');

            /** 
             * Set the Laravel locale
             */
            App::setLocale($locale);

            $lang = config('active_locales')->firstWhere('code', $locale);

            // Skip if the language not found
            if ($lang) {
                /** 
                 * SetLocale for php. Enables ->formatLocalized() with localized values for dates
                 */
                setlocale(LC_TIME, $lang->php_locale_code);

                /** 
                 * SetLocale to use Carbon source locales. Enables diffForHumans() localized
                 */
                Carbon::setLocale($lang->code);
                // Carbon::setLocale(config('locale.languages')[$locale][0]);

                /** 
                 * Set the session variable for whether or not the app is using RTL support
                 * for the current language being selected
                 * For use in the blade directive in BladeServiceProvider
                 */
                if ($lang->rtl) {
                    session(['lang-rtl' => true]);
                } else {
                    Session::forget('lang-rtl');
                }
            }
        }

        return $next($request);
    }
}
