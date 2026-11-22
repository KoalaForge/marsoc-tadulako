<?php

declare(strict_types=1);

namespace App\Filament\Shared\Schemas;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;

/**
 * The compact sibling of LocaleTabs, for use inside a Repeater row (the
 * creed lines, the identity formula cards, citations): a two-column
 * grid, one column per locale, rather than nested Tabs — nested Tabs
 * inside a Repeater row is visually unbearable at the row heights those
 * lists use.
 *
 * Fields inside each locale's closure are named "{attribute}.{locale}",
 * same convention as LocaleTabs.
 */
final class LocaleGrid
{
    /**
     * @param  Closure(string $locale): array<Component>  $components
     */
    public static function make(Closure $components): Grid
    {
        $locales = config('mrt.locales', ['id', 'en']);

        return Grid::make(count($locales))
            ->schema(array_map(
                fn (string $locale): Component => Grid::make(1)->schema($components($locale)),
                $locales,
            ));
    }
}
