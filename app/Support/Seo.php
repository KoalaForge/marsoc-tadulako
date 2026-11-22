<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\WebRoute;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\Personnel;
use App\Models\Publication;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Per-request <head> metadata, rendered server-side in app.blade.php so
 * crawlers and link-preview bots see it without running JavaScript (SSR
 * is intentionally off). Title/description come from the record for
 * detail pages, and from the Page row (falling back to the page's hero
 * block, then to SiteSettings defaults) for the 8 fixed pages.
 */
final class Seo
{
    private const SITE = 'M.R.T — Marine Raider Tadulako';

    private const PAGE_KEYS = [
        'web.home' => 'home',
        'web.about' => 'about',
        'web.personnel.index' => 'personnel',
        'web.impression' => 'impression',
        'web.activities.index' => 'activities',
        'web.media.index' => 'media',
        'web.publications.index' => 'publications',
        'web.join.index' => 'join',
    ];

    /**
     * @return array{title: string, description: string, canonical: string, alternates: array<string, string>, image: string, locale: string, type: string}
     */
    public static function forRequest(Request $request): array
    {
        return $request->attributes->get('seo') ?? tap(self::build($request), fn (array $seo) => $request->attributes->set('seo', $seo));
    }

    /**
     * @return array{title: string, description: string, canonical: string, alternates: array<string, string>, image: string, locale: string, type: string}
     */
    private static function build(Request $request): array
    {
        $locales = config('mrt.locales', ['id', 'en']);
        $locale = in_array($request->route('locale'), $locales, true) ? $request->route('locale') : config('mrt.default_locale', 'id');
        $settings = SiteSettings::data();

        [$title, $description, $image, $type] = self::resolve($request, $locale, $settings);

        $rest = trim((string) Str::after($request->path(), (string) $request->route('locale')), '/');
        $alternates = [];
        foreach ($locales as $candidate) {
            $alternates[$candidate] = url('/'.$candidate.($rest !== '' ? '/'.$rest : ''));
        }

        return [
            'title' => $title === self::SITE ? $title : "{$title} — M.R.T",
            'description' => Str::limit(trim(strip_tags($description)), 200),
            'canonical' => $alternates[$locale],
            'alternates' => $alternates,
            'image' => $image ?: url('/images/og-default.png'),
            'locale' => $locale,
            'type' => $type,
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{0: string, 1: string, 2: ?string, 3: string}
     */
    private static function resolve(Request $request, string $locale, array $settings): array
    {
        $name = (string) $request->route()?->getName();
        $defaultTitle = self::translated($settings['default_seo_title'] ?? null, $locale) ?: self::SITE;
        $defaultDescription = self::translated($settings['default_seo_description'] ?? null, $locale);

        if (isset(self::PAGE_KEYS[$name])) {
            $key = self::PAGE_KEYS[$name];
            $page = Page::query()->where('key', $key)->first();
            $hero = PageBlock::forKey("{$key}.hero");

            $title = self::trans($page, 'seo_title', $locale)
                ?: ($key === 'home' ? $defaultTitle : self::trans($hero, 'heading', $locale))
                ?: $defaultTitle;
            $description = self::trans($page, 'seo_description', $locale)
                ?: self::trans($hero, 'subheading', $locale)
                ?: $defaultDescription;
            $image = $page?->getFirstMediaUrl('og_image', 'wide') ?: null;

            return [$title, $description, $image ? Presenter::relativeUrl($image) : null, 'website'];
        }

        $record = match ($name) {
            WebRoute::PersonnelShow->value => Personnel::query()->where('callsign_lower', mb_strtolower((string) $request->route('callsign')))->first(),
            WebRoute::ActivitiesShow->value => Activity::query()->where('slug', $request->route('slug'))->first(),
            WebRoute::MediaShow->value => MediaItem::query()->where('key', $request->route('key'))->first(),
            WebRoute::PublicationsShow->value => Publication::query()->where('slug', $request->route('slug'))->first(),
            default => null,
        };

        return match (true) {
            $record instanceof Personnel => [$record->callsign, self::trans($record, 'bio', $locale), null, 'profile'],
            $record instanceof Activity => [self::trans($record, 'title', $locale), self::trans($record, 'overview', $locale), self::bannerUrl($record, 'banner'), 'article'],
            $record instanceof MediaItem => [self::trans($record, 'title', $locale), self::trans($record, 'caption', $locale), self::firstPhoto($record), 'website'],
            $record instanceof Publication => [self::trans($record, 'title', $locale), self::trans($record, 'excerpt', $locale), self::bannerUrl($record, 'cover'), 'article'],
            default => [$defaultTitle, $defaultDescription, null, 'website'],
        };
    }

    private static function bannerUrl(Activity|Publication $record, string $collection): ?string
    {
        $url = $record->getFirstMediaUrl($collection, 'wide');

        return $url === '' ? null : url(Presenter::relativeUrl($url));
    }

    private static function firstPhoto(MediaItem $item): ?string
    {
        $url = $item->getFirstMediaUrl('photos', 'wide');

        return $url === '' ? null : url(Presenter::relativeUrl($url));
    }

    private static function trans(?object $model, string $attribute, string $locale): string
    {
        return $model === null ? '' : (string) $model->getTranslation($attribute, $locale);
    }

    private static function translated(mixed $field, string $locale): string
    {
        return is_array($field) ? (string) ($field[$locale] ?? $field['id'] ?? '') : (string) $field;
    }
}
