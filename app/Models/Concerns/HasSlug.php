<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Generates a slug once, from the Indonesian title, on create only.
 *
 * Deliberately NOT regenerated when the title is edited later: slugs are
 * untranslatable by design so a URL stays stable once English copy exists —
 * silently re-slugging on every title edit would defeat that.
 *
 * @mixin Model
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function (self $model): void {
            if (filled($model->slug)) {
                return;
            }

            $model->slug = static::generateUniqueSlug($model->slugSourceText());
        });
    }

    /**
     * The text the slug is derived from — always the Indonesian ('id')
     * translation when the source attribute is translatable, never
     * whichever locale happens to be active while an admin is editing.
     * Override when the source isn't "title" or "name".
     */
    protected function slugSourceText(): string
    {
        $attribute = $this->hasAttribute('title') ? 'title' : 'name';

        if (in_array($attribute, $this->translatable, true)) {
            return (string) $this->getTranslation($attribute, 'id', useFallbackLocale: false);
        }

        return (string) $this->getAttribute($attribute);
    }

    protected static function generateUniqueSlug(string $source): string
    {
        $base = Str::slug($source) ?: Str::random(8);
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
