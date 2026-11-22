<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasSyntheticFlag;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Article. `author_callsign` is a plain string, not an ObjectId FK — the
 * byline renders as plain text, never linked, and an outside contributor
 * with no roster entry works naturally where an FK would force a fake
 * Personnel record.
 *
 * `content` is translatable rich text (HTML from the admin's rich editor,
 * inline images included), sanitized on output — see App\Support\Html.
 * `cover` is the optional banner image.
 */
final class Publication extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSlug;
    use HasSyntheticFlag;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'publications';

    /** @var list<string> */
    protected $fillable = [
        'slug',
        'title',
        'publication_category_id',
        'author_callsign',
        'published_on',
        'excerpt',
        'content',
        'sources',
        'related_activity_slug',
        'is_published',
        'is_synthetic',
    ];

    /** @var list<string> */
    public array $translatable = [
        'title',
        'excerpt',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'published_on' => 'date',
            // 'sources' deliberately uncast — see Activity's cast docblock:
            // Eloquent's 'array' cast json_encodes for SQL JSON columns,
            // which stores this as a string on Mongo instead of a native
            // BSON array.
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<PublicationCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeByAuthor(Builder $query, string $callsign): Builder
    {
        return $query->where('author_callsign', $callsign);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }
}
