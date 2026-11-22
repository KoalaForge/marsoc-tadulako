<?php

declare(strict_types=1);

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Caches a controller's already-shaped, plain-array prop data behind a
 * CacheVersion-keyed entry — see CacheVersion's own docblock for why a
 * version int, not Redis cache tags.
 *
 * The resolver MUST return a plain array (call ->toArray() / ->map->toArray()
 * on anything Eloquent), never a raw model, collection, or Inertia
 * defer/optional wrapper: config/cache.php sets 'serializable_classes' =>
 * false, so any object reaching Redis — nested or not — comes back as
 * __PHP_Incomplete_Class on the next cache hit.
 *
 * Deliberately not locale-keyed: every translatable attribute ships its
 * full {id, en} map regardless of the active locale (the client picks),
 * so one cache entry already serves both locales.
 */
final class CachedProps
{
    public static function remember(string $key, Closure $resolver): mixed
    {
        return Cache::remember(
            "props:{$key}:v".CacheVersion::current(),
            now()->addDay(),
            $resolver,
        );
    }
}
