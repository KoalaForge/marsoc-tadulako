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
 * Field-log event. `related_member_callsigns` is a plain string array,
 * not a belongsToMany — laravel-mongodb's m2m writes a key array on BOTH
 * documents with no transaction protecting the pair; a one-sided array
 * is a single atomic write. The reverse query (Personnel's "Activity
 * Participation") is `Activity::where('related_member_callsigns', $callsign)`,
 * served by the multikey index in the activities migration.
 *
 * `banner` is the activity's own header image (single file). Photos from
 * the media archive still attach through MediaItem.related_activity_slug,
 * which keeps the "View in Media Archive" link.
 *
 * `content` is rich text (HTML from the admin's rich editor); it is
 * sanitized on output — see App\Support\Html.
 */
final class Activity extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSlug;
    use HasSyntheticFlag;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'activities';

    /** @var list<string> */
    protected $fillable = [
        'slug',
        'title',
        'activity_category_id',
        'occurred_on',
        'location',
        'grid',
        'participants',
        'related_member_callsigns',
        'overview',
        'content',
        'is_published',
        'is_synthetic',
    ];

    /** @var list<string> */
    public array $translatable = [
        'title',
        'overview',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
            'participants' => 'integer',
            // Deliberately NOT 'array': Eloquent's array cast round-trips
            // through json_encode/json_decode (built for SQL JSON
            // columns), which stores this as a JSON STRING on Mongo
            // instead of a native BSON array — breaking the multikey
            // index and the scalar-containment query the reverse lookup
            // on Personnel depends on. Leaving it uncast lets the BSON
            // driver's native array handling do the right thing.
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<ActivityCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeLatest3(Builder $query): Builder
    {
        return $query->orderByDesc('occurred_on')->limit(3);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')->singleFile();
    }
}
