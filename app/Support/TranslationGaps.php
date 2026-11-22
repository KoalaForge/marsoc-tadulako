<?php

declare(strict_types=1);

namespace App\Support;

use App\Filament\Pages\Content\HomeContentPage;
use App\Filament\Resources\Activities\ActivityResource;
use App\Filament\Resources\Eras\EraResource;
use App\Filament\Resources\MediaItems\MediaItemResource;
use App\Filament\Resources\Personnels\PersonnelResource;
use App\Filament\Resources\Publications\PublicationResource;
use App\Models\Activity;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\PageBlock;
use App\Models\Personnel;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Model;

/**
 * Finds content where one locale is filled and another is blank — the one
 * invisible failure mode of ID-first bilingual editing (the English site
 * silently falls back to Indonesian). Fields blank in EVERY locale are
 * treated as intentionally empty, not as gaps.
 */
final class TranslationGaps
{
    /**
     * @return list<array{type: string, label: string, fields: list<string>, url: string}>
     */
    public static function scan(): array
    {
        $gaps = [];

        $sources = [
            ['Personel', Personnel::class, PersonnelResource::class, 'callsign', ['role', 'impression_focus', 'bio']],
            ['Kegiatan', Activity::class, ActivityResource::class, 'slug', ['title', 'overview', 'content']],
            ['Media', MediaItem::class, MediaItemResource::class, 'key', ['title', 'caption']],
            ['Publikasi', Publication::class, PublicationResource::class, 'slug', ['title', 'excerpt', 'content']],
            ['Era', Era::class, EraResource::class, 'key', ['uniform', 'gear', 'helmet']],
        ];

        foreach ($sources as [$type, $model, $resource, $labelAttribute, $fields]) {
            foreach ($model::query()->get() as $record) {
                $missing = self::missingFields($record, $fields);

                if ($missing !== []) {
                    $gaps[] = [
                        'type' => $type,
                        'label' => (string) $record->getAttribute($labelAttribute),
                        'fields' => $missing,
                        'url' => $resource::getUrl('edit', ['record' => $record]),
                    ];
                }
            }
        }

        foreach (PageBlock::query()->where('page_key', '!=', '_global')->get() as $block) {
            $missing = self::missingFields($block, ['eyebrow', 'heading', 'subheading', 'body', 'footnote']);

            if ($missing !== []) {
                $gaps[] = [
                    'type' => 'Halaman '.$block->page_key,
                    'label' => $block->block_key,
                    'fields' => $missing,
                    'url' => self::pageEditorUrl($block->page_key),
                ];
            }
        }

        return $gaps;
    }

    /**
     * @param  list<string>  $fields
     * @return list<string>
     */
    private static function missingFields(Model $record, array $fields): array
    {
        $locales = config('mrt.locales', ['id', 'en']);
        $missing = [];

        if (! method_exists($record, 'getTranslation')) {
            return $missing;
        }

        foreach ($fields as $field) {
            $values = array_map(
                fn (string $locale): string => trim((string) $record->getTranslation($field, $locale, useFallbackLocale: false)),
                $locales,
            );
            $filled = count(array_filter($values, fn (string $v): bool => $v !== ''));

            if ($filled > 0 && $filled < count($locales)) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    private static function pageEditorUrl(string $pageKey): string
    {
        $class = '\\App\\Filament\\Pages\\Content\\'.ucfirst($pageKey).'ContentPage';

        return class_exists($class) ? $class::getUrl() : HomeContentPage::getUrl();
    }
}
