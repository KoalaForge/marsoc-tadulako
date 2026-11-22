<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Resolves the hand-rolled `{en, id}` translation convention used inside
 * PageBlock::$items (spatie-translatable only covers a model's own
 * scalar attributes, not nested array structures). Mirrors the
 * frontend's own `localized()` helper in resources/js/lib/localized.ts.
 */
final class Localize
{
    public static function pick(mixed $field, string $locale): mixed
    {
        if ($field === null) {
            return null;
        }

        if (is_string($field)) {
            return $field;
        }

        if (is_array($field)) {
            return $field[$locale] ?? $field['en'] ?? reset($field);
        }

        return $field;
    }
}
