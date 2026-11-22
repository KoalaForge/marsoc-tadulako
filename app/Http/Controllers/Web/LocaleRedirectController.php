<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Resolves the bare "/" to a locale-prefixed path: cookie (a prior
 * explicit choice) -> Accept-Language -> config default. A 302, not a
 * 301 — the visitor's preference can change.
 */
final class LocaleRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $locales = config('mrt.locales', ['id', 'en']);

        $locale = $request->cookie('mrt_locale');

        if (! in_array($locale, $locales, strict: true)) {
            $locale = $request->getPreferredLanguage($locales);
        }

        if (! in_array($locale, $locales, strict: true)) {
            $locale = config('mrt.default_locale', 'id');
        }

        return redirect("/{$locale}");
    }
}
