<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\CacheVersion;

/**
 * Bumps the global content cache version on any save or delete. Every
 * public-facing content model uses this; the version int is what makes
 * CacheVersion::current()-keyed prop caches invalidate correctly after
 * an admin edit, without Redis-only cache tags. See App\Support\CacheVersion.
 */
trait TouchesCacheVersion
{
    public static function bootTouchesCacheVersion(): void
    {
        static::saved(fn (): int => CacheVersion::bump());
        static::deleted(fn (): int => CacheVersion::bump());
    }
}
