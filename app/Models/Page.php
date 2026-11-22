<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * One of exactly 8 fixed rows (see PageKey) plus `_global` — not
 * creatable or deletable from Filament. Exists mainly so `og_image` has
 * a medialibrary owner; page_blocks reference it by `page_key` string,
 * not `page_id` ObjectId, so the per-page block query never needs to
 * load this document at all.
 */
final class Page extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'pages';

    /** @var list<string> */
    protected $fillable = [
        'key',
        'seo_title',
        'seo_description',
        'nav_label',
        'sort',
        'is_published',
    ];

    /** @var list<string> */
    public array $translatable = [
        'seo_title',
        'seo_description',
        'nav_label',
    ];

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    /**
     * @return HasMany<PageBlock, $this>
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class, 'page_key', 'key')->orderBy('sort');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')->singleFile();
    }
}
