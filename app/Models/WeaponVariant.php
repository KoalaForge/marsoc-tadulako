<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WeaponCategory;
use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * A single weapon reference plate (Impression page §03). `name` is a
 * plain string — technical designations, never translated. `category`
 * groups variants via the WeaponCategory enum, not a second collection.
 */
final class WeaponVariant extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSlug;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'weapon_variants';

    /** @var list<string> */
    protected $fillable = [
        'key',
        'category',
        'name',
        'description',
        'sort',
        'is_published',
    ];

    /** @var list<string> */
    public array $translatable = [
        'description',
    ];

    protected function casts(): array
    {
        return [
            'category' => WeaponCategory::class,
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

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeCategory(Builder $query, WeaponCategory $category): Builder
    {
        return $query->where('category', $category->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos');
    }
}
