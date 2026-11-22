<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * Overrides Spatie's Media model to point at the dedicated `sqlite_media`
 * connection — see config/database.php for why. Wired via
 * config('media-library.media_model').
 */
final class Media extends BaseMedia
{
    protected $connection = 'sqlite_media';
}
