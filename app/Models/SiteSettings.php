<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithSiteMedia;
use Illuminate\Support\Facades\Cache;
use MongoDB\BSON\ObjectId;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Singleton (always the first/only document — see data()). Caches a
 * plain array, never a hydrated model: config/cache.php sets
 * 'serializable_classes' => false, so any object reaching Redis —
 * including the Carbon and BSON\ObjectId instances nested in a model's
 * raw attributes — comes back as __PHP_Incomplete_Class on the next
 * cache hit. Returning array is strictly safer than mrt's
 * hydrate-a-ghost-model approach: no object crosses the cache boundary
 * at all, so nothing can accidentally ->save() a stale cached instance.
 */
final class SiteSettings extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithSiteMedia;

    protected $connection = 'mongodb';

    protected $collection = 'site_settings';

    /** @var list<string> */
    protected $fillable = [
        'whatsapp_number',
        'whatsapp_message_heading',
        'community_name',
        'callsign_short',
        'tagline',
        'home_grid_readout',
        'copyright_holder',
        'copyright_start_year',
        'disclaimer',
        'default_seo_title',
        'default_seo_description',
    ];

    /** @var list<string> */
    public array $translatable = [
        'whatsapp_message_heading',
        'community_name',
        'disclaimer',
        'default_seo_title',
        'default_seo_description',
    ];

    protected function casts(): array
    {
        return [
            'copyright_start_year' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function data(): array
    {
        return Cache::remember(
            'mrt:site-settings',
            3600,
            fn (): array => self::toCacheableAttributes(self::query()->firstOrCreate([])->getAttributes()),
        );
    }

    /**
     * spatie-translatable stores each translated field as a JSON string
     * (it merges an 'array' cast), so raw attributes carry '{"id":..}'
     * strings. Decode them so every consumer of data() gets real {id, en}
     * maps and never leaks JSON text into the UI.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private static function toCacheableAttributes(array $attributes): array
    {
        $translatable = (new self)->getTranslatableAttributes();

        foreach ($attributes as $key => $value) {
            $attributes[$key] = match (true) {
                $value instanceof \DateTimeInterface => $value->format(\DATE_ATOM),
                $value instanceof ObjectId => (string) $value,
                in_array($key, $translatable, true) && is_string($value) => json_decode($value, true) ?? $value,
                default => $value,
            };
        }

        return $attributes;
    }

    public static function clearCache(): void
    {
        Cache::forget('mrt:site-settings');
    }

    protected static function booted(): void
    {
        self::saved(static function (): void {
            self::clearCache();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo_color')->singleFile();
        $this->addMediaCollection('logo_gold_on_black')->singleFile();
        $this->addMediaCollection('logo_gold_transparent')->singleFile();
        $this->addMediaCollection('logo_white_on_black')->singleFile();
        $this->addMediaCollection('logo_white_transparent')->singleFile();
        $this->addMediaCollection('logo_black_transparent')->singleFile();
    }
}
