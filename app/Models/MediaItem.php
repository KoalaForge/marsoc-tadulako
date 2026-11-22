<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCategory;
use App\Enums\MediaType;
use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasSyntheticFlag;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Media Archive record. Named `MediaItem` / `media_items`, never `Media`
 * — `App\Models\Media` is already taken by the Spatie override pinned to
 * the `sqlite_media` connection.
 *
 * `type` (render mode: photo/demo/placeholder) and `is_synthetic`
 * (provenance) are deliberately two different axes — see the trait/enum
 * docblocks. `weapon_id` is a soft, nullable pointer for the 7 photos
 * shared with a WeaponVariant (medialibrary attaches to one owner only,
 * so the bytes are duplicated; this field just makes the coupling
 * discoverable in the admin).
 */
final class MediaItem extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSlug;
    use HasSyntheticFlag;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'media_items';

    /** @var list<string> */
    protected $fillable = [
        'key',
        'category',
        'type',
        'title',
        'caption',
        'year_label',
        'related_activity_slug',
        'weapon_id',
        'sort',
        'is_published',
        'is_synthetic',
    ];

    /** @var list<string> */
    public array $translatable = [
        'title',
        'caption',
    ];

    protected function casts(): array
    {
        return [
            'category' => MediaCategory::class,
            'type' => MediaType::class,
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
