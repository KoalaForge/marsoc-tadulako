<?php

declare(strict_types=1);

namespace App\Models\Concerns;

/**
 * Same no-column-DEFAULT problem as HasSyntheticFlag. Defaults to false:
 * an account nobody explicitly marked as admin must not get panel access
 * just because the attribute was never set.
 */
trait HasAdminFlag
{
    public function initializeHasAdminFlag(): void
    {
        $this->mergeCasts(['is_admin' => 'boolean']);

        if (! array_key_exists('is_admin', $this->attributes)) {
            $this->attributes['is_admin'] = false;
        }
    }
}
