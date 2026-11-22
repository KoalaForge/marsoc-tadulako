<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Uniform/gear/helmet era reference (Impression page §02). `name` is a
 * plain string — era names are brand/technical terms that stay identical
 * in both locales by design, never translated.
 */
final class Era extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSlug;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'eras';

    /** @var list<string> */
    protected $fillable = [
        'key',
        'name',
        'uniform',
        'gear',
        'helmet',
        'source_note',
        'sort',
        'is_published',
    ];

    /** @var list<string> */
    public array $translatable = [
        'uniform',
        'gear',
        'helmet',
        'source_note',
    ];

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
        ];
    }

    /** The URL key is generated from the title/name when left empty. */
    protected function slugAttribute(): string
    {
        return 'key';
    }

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos');
    }
}
