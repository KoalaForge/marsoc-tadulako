<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SiteSettings;
use App\Support\Seo;
use App\Support\WhatsAppLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * @see https://inertiajs.com/server-side-setup#root-template
     */
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locales = config('mrt.locales', ['id', 'en']);

        // Reads the {locale} route segment directly rather than
        // app()->getLocale(): the route is already matched by the time any
        // middleware in the pipeline runs, but SetLocale (a route-group
        // middleware) executes AFTER the 'web' group's middleware — which
        // is where this class is appended in bootstrap/app.php — so
        // App::setLocale() has not happened yet when share() runs. Reading
        // the route parameter sidesteps that ordering entirely.
        $routeLocale = $request->route('locale');
        $locale = in_array($routeLocale, $locales, strict: true)
            ? $routeLocale
            : config('mrt.default_locale', 'id');

        return [
            ...parent::share($request),
            'locale' => $locale,
            'locales' => $locales,
            'localeUrls' => $this->mirroredUrls($request, $locales),
            'translations' => $this->uiStrings($locale),
            'site' => fn (): array => $this->site(),
            'seoTitle' => fn (): string => Seo::forRequest($request)['title'],
        ];
    }

    /**
     * Site-wide admin-editable values the chrome needs on every page.
     *
     * @return array<string, mixed>
     */
    private function site(): array
    {
        $settings = SiteSettings::data();
        $whatsapp = WhatsAppLink::fromSettings();
        $start = (int) ($settings['copyright_start_year'] ?? now()->year);

        return [
            'whatsappBase' => $whatsapp?->url(),
            'disclaimer' => $settings['disclaimer'] ?? null,
            'copyrightHolder' => $settings['copyright_holder'] ?? 'M.R.T',
            'copyrightYears' => $start >= now()->year ? (string) $start : "{$start}–".now()->year,
        ];
    }

    /**
     * Swaps the first path segment to build every locale's equivalent
     * URL — a pure string operation, no DB lookup, because slugs are
     * deliberately locale-independent. Powers both the locale switch
     * and <link rel="alternate" hreflang> in app.blade.php.
     *
     * @param  list<string>  $locales
     * @return array<string, string>
     */
    private function mirroredUrls(Request $request, array $locales): array
    {
        $segments = explode('/', trim($request->path(), '/'));
        array_shift($segments);

        $rest = implode('/', $segments);

        $urls = [];

        foreach ($locales as $candidate) {
            $urls[$candidate] = '/'.$candidate.($rest !== '' ? '/'.$rest : '');
        }

        return $urls;
    }

    /**
     * Full locale JSON blob for the ACTIVE locale only — halves the
     * payload versus shipping every locale, and a locale switch is
     * already a full navigation (new URL), so there is no client-side
     * need for the other locale's strings. See lang/{locale}.json.
     *
     * Today this ships every key, chrome and content alike — the
     * content half migrates to the page_blocks CMS once it exists;
     * until then this is a faithful, unmodified copy of the frontend's
     * own i18n source so every page renders identically.
     *
     * @return array<string, mixed>
     */
    private function uiStrings(string $locale): array
    {
        $path = lang_path("{$locale}.json");

        if (! File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true) ?? [];
    }
}
