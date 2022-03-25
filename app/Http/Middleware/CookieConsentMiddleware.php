<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Response;

class CookieConsentMiddleware
{
    /**
     * GDPR Cookie Consent
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ignore the selling theme routes
        if ($request->is('selling*')) {
            return $response;
        }

        if ($request->ajax()) {
            return $response;
        }

        if (! config('gdpr.cookie.enabled')) {
            return $response;
        }

        if (Cookie::has(config('gdpr.cookie.name'))) {
            return $response;
        }

        if (! $response instanceof Response) {
            return $response;
        }

        if (! $this->containsBodyTag($response)) {
            return $response;
        }

        return $this->addCookieConsentScriptToResponse($response);
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return $this
     */
    protected function containsBodyTag(Response $response): bool
    {
        return $this->getLastClosingBodyTagPosition($response->getContent()) !== false;
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return $this
     */
    protected function addCookieConsentScriptToResponse(Response $response)
    {
        $content = $response->getContent();

        $closingBodyTagPosition = $this->getLastClosingBodyTagPosition($content);

        $content = ''
            .substr($content, 0, $closingBodyTagPosition)
            .view('cookie_consent')->render()
            .substr($content, $closingBodyTagPosition);

        return $response->setContent($content);
    }

    protected function getLastClosingBodyTagPosition(string $content = '')
    {
        return strripos($content, '</body>');
    }
}
