<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeLocaleByHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locales = ['pl', 'en'];

        if ($language = $request->getPreferredLanguage($locales)) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
