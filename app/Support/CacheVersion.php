<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * A single incrementing integer, not Redis cache tags: a version int
 * works on any cache store and needs no per-model tag bookkeeping to
 * drift out of sync. Bumped by any content model's save/delete — see
 * TouchesCacheVersion.
 *
 * Usage: Cache::remember("page:{$key}:v" . CacheVersion::current(), ...)
 */
final class CacheVersion
{
    private const string KEY = 'mrt:content-cache-version';

    public static function current(): int
    {
        return (int) Cache::get(self::KEY, 1);
    }

    public static function bump(): int
    {
        if (! Cache::has(self::KEY)) {
            Cache::forever(self::KEY, 1);
        }

        return Cache::increment(self::KEY);
    }
}
