<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PageBlock;

/**
 * Fetches a page's published blocks and re-keys them by the short
 * suffix (the part after "{page_key}.") so Vue props read as
 * `page.blocks.formula`, `page.blocks.creed`, etc. — one query per
 * page, matching the CMS spine's whole reason for being flat rather
 * than nested.
 */
final class PageContent
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function blocks(string $pageKey): array
    {
        $prefix = "{$pageKey}.";

        return PageBlock::query()
            ->where('page_key', $pageKey)
            ->where('is_published', true)
            ->orderBy('sort')
            ->get()
            ->mapWithKeys(function (PageBlock $block) use ($prefix): array {
                $suffix = str_starts_with($block->block_key, $prefix)
                    ? substr($block->block_key, strlen($prefix))
                    : $block->block_key;

                return [$suffix => $block->toArray()];
            })
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function global(string $blockKey): ?array
    {
        return PageBlock::forKey("_global.{$blockKey}")?->toArray();
    }
}
