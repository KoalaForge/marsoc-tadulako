<?php

declare(strict_types=1);

namespace App\Filament\Shared\Schemas;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Renders one Filament Tab per configured locale, side by side rather
 * than behind a global locale switcher. Fields inside each tab's
 * $components closure are expected to be named "{attribute}.{locale}"
 * (dot notation into the translatable JSON array state Filament holds
 * while editing).
 *
 * IMPORTANT — never call ->required() unconditionally inside the
 * $components closure: it applies to EVERY locale's tab identically,
 * including EN, which blocks saving ID-only content before a translator
 * backfills EN (the whole point of the fallback-locale design). Gate it
 * on the locale instead: ->required($locale === 'id').
 */
final class LocaleTabs
{
    /**
     * @param  Closure(string $locale): array<Component>  $components
     */
    public static function make(Closure $components, ?string $label = null): Tabs
    {
        $locales = config('mrt.locales', ['id', 'en']);

        $tabs = array_map(
            fn (string $locale): Tab => Tab::make(mb_strtoupper($locale))
                ->schema($components($locale)),
            $locales,
        );

        return Tabs::make($label)->tabs($tabs);
    }

    /**
     * Resolves an itemLabel for a Repeater whose rows carry a
     * translation map under one of the given keys — falls through
     * ID -> EN -> null (Filament then shows a generic "Item N"), so a
     * half-filled row never renders a blank collapsed bar.
     *
     * @param  list<string>  $keys
     */
    public static function itemLabel(array $keys): Closure
    {
        return function (array $state) use ($keys): ?string {
            foreach (['id', 'en'] as $locale) {
                foreach ($keys as $key) {
                    $value = data_get($state, "{$key}.{$locale}");
                    if (filled($value)) {
                        return $value;
                    }
                }
            }

            return null;
        };
    }
}
