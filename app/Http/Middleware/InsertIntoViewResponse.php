<?php

namespace App\Http\Middleware;

use App\Services\ResponseManipulation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InsertIntoViewResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->is('selling*') || $request->ajax() || !$response instanceof Response) {
            return $response;
        }

        $contents = [
            '</head>' => view('analytic_script')->render(),
            // '</body>' => view('cookie_consent')->render(),
        ];

        foreach ($contents as $tag => $content) {
            $manipulator = new ResponseManipulation($tag, $content, $response);

            $response = $manipulator->getResponse();
        }

        return $response;
    }
}
