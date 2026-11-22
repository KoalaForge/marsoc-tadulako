<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the {locale} route-prefix segment app-wide and writes a
 * long-lived cookie used ONLY by LocaleRedirectController's bare "/"
 * redirect — never as a content-selection mechanism itself.
 */
final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        $response = $next($request);

        $response->headers->setCookie(
            Cookie::create('mrt_locale', $locale)
                ->withExpires(now()->addYear())
                ->withPath('/')
                ->withSameSite('lax'),
        );

        return $response;
    }
}
