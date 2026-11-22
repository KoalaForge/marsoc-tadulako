<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\MediaCategory;
use App\Enums\PersonnelStatus;
use App\Enums\WeaponCategory;
use App\Models\Activity;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use App\Models\WeaponVariant;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;

/**
 * Turns Eloquent models into the exact plain-array prop shapes the Vue
 * pages were built against (the camelCase fixture contract), so swapping
 * fixtures for the database changed no frontend code. Every result is a
 * plain array — safe to hand to CachedProps (no objects reach the cache).
 *
 * Translatable fields ship as their full {id, en} map; the client picks.
 */
final class Presenter
{
    /** @return array<string, mixed> */
    public static function member(Personnel $p, ?Era $era = null): array
    {
        $status = $p->status instanceof PersonnelStatus ? $p->status : PersonnelStatus::from((string) $p->status);

        return [
            'callsign' => $p->callsign,
            'memberId' => $p->member_id,
            'role' => $p->getTranslations('role'),
            'statusKey' => $status->value,
            'status' => ['en' => ucfirst($status->value), 'id' => $status->getLabel()],
            'joinDate' => $p->joined_on,
            'impressionEra' => $era === null ? null : ['en' => $era->name, 'id' => $era->name],
            'impressionFocus' => $p->getTranslations('impression_focus'),
            'primaryPlatform' => $p->primary_platform,
            'secondaryPlatform' => $p->secondary_platform ?? '—',
            'grid' => $p->grid,
            'bio' => $p->getTranslations('bio'),
        ];
    }

    /**
     * @param  Collection<int, Personnel>  $members
     * @return list<array<string, mixed>>
     */
    public static function members(Collection $members): array
    {
        $eras = Era::query()->get()->keyBy(fn (Era $e): string => (string) $e->getKey());

        return $members
            ->map(fn (Personnel $p): array => self::member($p, $eras->get((string) $p->era_id)))
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    public static function activity(Activity $a): array
    {
        $category = $a->category;

        return [
            'slug' => $a->slug,
            'title' => $a->getTranslations('title'),
            'category' => $category?->getTranslations('name') ?? [],
            'date' => $a->occurred_on?->toDateString(),
            'location' => $a->location,
            'grid' => $a->grid,
            'participants' => $a->participants,
            'relatedMembers' => self::plainList($a->related_member_callsigns),
            'overview' => $a->getTranslations('overview'),
            'content' => array_map(Html::sanitize(...), $a->getTranslations('content')),
            'banner' => self::mediaUrl($a, 'banner', 'wide'),
            'thumb' => self::mediaUrl($a, 'banner', 'card'),
        ];
    }

    /**
     * @param  Collection<int, Activity>  $activities
     * @return list<array<string, mixed>>
     */
    public static function activities(Collection $activities): array
    {
        return $activities->map(fn (Activity $a): array => self::activity($a))->values()->all();
    }

    /** @return array<string, mixed> */
    public static function mediaItem(MediaItem $m): array
    {
        $category = $m->category instanceof MediaCategory ? $m->category : MediaCategory::from((string) $m->category);

        return array_filter([
            'id' => $m->key,
            'category' => $category->labelEn(),
            'type' => $m->type instanceof \BackedEnum ? $m->type->value : $m->type,
            'photos' => self::photoUrls($m),
            'title' => $m->getTranslations('title'),
            'caption' => $m->getTranslations('caption'),
            'year' => $m->year_label,
            'relatedActivity' => $m->related_activity_slug,
        ], fn (mixed $v): bool => $v !== null);
    }

    /** @return array{categories: list<string>, categoryLabels: array<string, array<string, string>>} */
    public static function mediaCategories(): array
    {
        $labels = ['en' => [], 'id' => []];

        foreach (MediaCategory::cases() as $case) {
            $labels['en'][$case->labelEn()] = $case->labelEn();
            $labels['id'][$case->labelEn()] = $case->getLabel();
        }

        return ['categories' => array_keys($labels['en']), 'categoryLabels' => $labels];
    }

    /** @return array<string, mixed> */
    public static function publication(Publication $p): array
    {
        return array_filter([
            'slug' => $p->slug,
            'title' => $p->getTranslations('title'),
            'category' => $p->category?->getTranslations('name') ?? [],
            'author' => $p->author_callsign,
            'date' => $p->published_on?->toDateString(),
            'excerpt' => $p->getTranslations('excerpt'),
            'content' => array_map(Html::sanitize(...), $p->getTranslations('content')),
            'banner' => self::mediaUrl($p, 'cover', 'wide'),
            'thumb' => self::mediaUrl($p, 'cover', 'card'),
            'sources' => array_map(
                fn (array $s): string => (string) ($s['label'] ?? ''),
                self::plainList($p->sources),
            ),
            'relatedActivity' => $p->related_activity_slug,
        ], fn (mixed $v): bool => $v !== null);
    }

    /** @return list<array<string, mixed>> */
    public static function eras(): array
    {
        return Era::query()->where('is_published', true)->orderBy('sort')->get()
            ->map(fn (Era $e): array => [
                'key' => $e->key,
                'name' => $e->name,
                'images' => self::photoUrls($e),
                'uniform' => $e->getTranslations('uniform'),
                'gear' => $e->getTranslations('gear'),
                'helmet' => $e->getTranslations('helmet'),
            ])->values()->all();
    }

    /** @return list<array<string, mixed>> */
    public static function weaponCategories(): array
    {
        $variants = WeaponVariant::query()->where('is_published', true)->orderBy('sort')->get()
            ->groupBy(fn (WeaponVariant $w): string => $w->category instanceof WeaponCategory ? $w->category->value : (string) $w->category);

        $result = [];

        foreach (WeaponCategory::cases() as $case) {
            $items = $variants->get($case->value);

            if ($items === null) {
                continue;
            }

            $result[] = [
                'categoryKey' => $case->value,
                'category' => $case->getLabel(),
                'items' => $items->map(fn (WeaponVariant $w): array => [
                    'key' => $w->key,
                    'name' => $w->name,
                    'images' => self::photoUrls($w),
                ])->values()->all(),
            ];
        }

        return $result;
    }

    private static function mediaUrl(HasMedia $model, string $collection, string $conversion): ?string
    {
        $media = $model->getFirstMedia($collection);

        if ($media === null) {
            return null;
        }

        return self::relativeUrl($media->hasGeneratedConversion($conversion) ? $media->getUrl($conversion) : $media->getUrl());
    }

    /** @return list<string> */
    private static function photoUrls(HasMedia $model): array
    {
        return $model->getMedia('photos')
            ->map(fn ($media): string => self::relativeUrl(
                $media->hasGeneratedConversion('slider') ? $media->getUrl('slider') : $media->getUrl(),
            ))
            ->values()
            ->all();
    }

    /**
     * Local-disk media URLs are built from APP_URL, which is wrong the
     * moment the site is reached on another host/port. Same-origin paths
     * are always right; remote (S3/CDN) URLs pass through untouched.
     */
    public static function relativeUrl(string $url): string
    {
        $base = rtrim((string) config('app.url'), '/');

        return str_starts_with($url, $base.'/') ? substr($url, strlen($base)) : $url;
    }

    /**
     * Mongo hands back BSONArray/BSONDocument objects; flatten to plain PHP.
     *
     * @return list<mixed>
     */
    private static function plainList(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        return array_values((array) json_decode((string) json_encode($value), true));
    }
}
