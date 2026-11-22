<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Same no-column-DEFAULT problem as HasSyntheticFlag, but defaulting to
 * true: a record an admin creates without touching the toggle should be
 * visible on the public site, not silently hidden.
 */
trait HasPublishedFlag
{
    public function initializeHasPublishedFlag(): void
    {
        $this->mergeCasts(['is_published' => 'boolean']);

        if (! array_key_exists('is_published', $this->attributes)) {
            $this->attributes['is_published'] = true;
        }
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
