<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BlockLayout;
use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\TouchesCacheVersion;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * The CMS spine. One document per page section — a flat collection with
 * embedded, repeating `items[]`, not mrt's nested Page -> PageSection ->
 * SectionItem shape: one query and one atomic write per section, which
 * matters because there are no multi-document transactions on standalone
 * Mongo.
 *
 * `block_key` is globally unique, not unique-per-page, so a block like
 * `_global.disclaimer` can be fetched once by key and rendered in both
 * the footer and the About page's disclaimer section.
 *
 * The block's own scalar fields (heading/subheading/body/footnote) use
 * spatie HasTranslations. The embedded `items[]` array uses a
 * hand-rolled `{en, id}` convention on a plain array cast instead —
 * spatie does not translate nested arrays. See App\Support\Localize.
 */
final class PageBlock extends Model
{
    use HasPublishedFlag;
    use HasTranslations;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'page_blocks';

    /** @var list<string> */
    protected $fillable = [
        'page_key',
        'block_key',
        'layout',
        'sort',
        'eyebrow',
        'heading',
        'subheading',
        'body',
        'footnote',
        'items',
        'settings',
        'is_published',
    ];

    /** @var list<string> */
    public array $translatable = [
        'eyebrow',
        'heading',
        'subheading',
        'body',
        'footnote',
    ];

    protected function casts(): array
    {
        return [
            'layout' => BlockLayout::class,
            // 'items' and 'settings' deliberately uncast — see Activity's
            // cast docblock: Eloquent's 'array' cast json_encodes for SQL
            // JSON columns, storing these as strings on Mongo instead of
            // native BSON arrays/documents.
            'sort' => 'integer',
        ];
    }

    public static function forKey(string $blockKey): ?self
    {
        return self::query()->where('block_key', $blockKey)->first();
    }
}
