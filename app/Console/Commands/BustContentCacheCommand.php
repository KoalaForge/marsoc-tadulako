<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\CacheVersion;
use Illuminate\Console\Command;

/**
 * Invalidates every cached public-page prop set (CachedProps) by bumping the
 * content cache version. The version normally only moves when an admin saves
 * content, so a deploy that changes HOW a page is built (ordering, fields)
 * would otherwise keep serving the old cached shape until the next edit or
 * the 24h TTL. Runs on every container start (docker/start-container).
 * It touches only this app's own version key — never a FLUSHDB on the
 * shared Redis.
 */
final class BustContentCacheCommand extends Command
{
    protected $signature = 'content:cache-bust';

    protected $description = 'Invalidate cached public page props (bumps the content cache version)';

    public function handle(): int
    {
        $this->info('Content cache version is now '.CacheVersion::bump().'.');

        return self::SUCCESS;
    }
}
