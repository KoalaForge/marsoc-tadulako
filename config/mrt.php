<?php

declare(strict_types=1);

return [
    'locales' => ['id', 'en'],
    'default_locale' => 'id',
    'cache_ttl' => 86400,
    // Seeders upload demo images through the media library (slow: inline
    // conversions). Tests switch this off.
    'seed_media' => (bool) env('MRT_SEED_MEDIA', true),
];
