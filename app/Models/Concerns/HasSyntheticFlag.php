<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Flags seeded demo content so it is queryable and findable — never a
 * naming convention like a "[SAMPLE]" title prefix, which an admin would
 * have to remember to strip and would silently ship if forgotten.
 *
 * Models using this trait must have an `is_synthetic` boolean field
 * (default false).
 */
trait HasSyntheticFlag
{
    public function initializeHasSyntheticFlag(): void
    {
        $this->mergeCasts(['is_synthetic' => 'boolean']);

        // MongoDB is schemaless: there is no column-level DEFAULT false
        // the way a SQL migration would declare one, so an attribute
        // nobody set at create time reads back as null, not false.
        // Seed it here, before fill() runs, so an explicit value in
        // create()/fill() still overrides it normally.
        if (! array_key_exists('is_synthetic', $this->attributes)) {
            $this->attributes['is_synthetic'] = false;
        }
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeSynthetic(Builder $query): Builder
    {
        return $query->where('is_synthetic', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_synthetic', false);
    }

    public function markAsReal(): bool
    {
        return $this->forceFill(['is_synthetic' => false])->save();
    }
}
