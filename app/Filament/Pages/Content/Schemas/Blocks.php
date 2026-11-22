<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content\Schemas;

use App\Filament\Shared\Schemas\LocaleGrid;
use App\Filament\Shared\Schemas\LocaleTabs;
use App\Models\PageBlock;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;

/**
 * Small composition helpers shared by the page-content editors. Each
 * editor still decides which blocks/fields exist; these only avoid
 * re-typing the LocaleTabs/Repeater boilerplate. Field names use dot
 * paths into the form's `data` state: "{blockSuffix}.{field}.{locale}".
 */
final class Blocks
{
    /**
     * @param  array<string, string|array{0: string, 1: 'text'|'area'}>  $fields  attribute => label, or [label, 'area']
     * @param  list<Component>  $extra  components placed after the locale tabs
     */
    public static function section(string $title, string $block, array $fields, array $extra = [], ?string $description = null): Section
    {
        return Section::make($title)
            ->description($description)
            ->collapsible()
            ->schema([
                LocaleTabs::make(function (string $locale) use ($block, $fields): array {
                    $components = [];

                    foreach ($fields as $attribute => $spec) {
                        [$label, $type] = is_array($spec) ? $spec : [$spec, 'text'];
                        $name = "{$block}.{$attribute}.{$locale}";

                        $components[] = ($type === 'area'
                            ? Textarea::make($name)->rows(5)
                            : TextInput::make($name))
                            ->label("{$label} ({$locale})")
                            // Only ID is required: see LocaleTabs docblock.
                            ->required($locale === 'id' && $attribute !== 'footnote' && $attribute !== 'eyebrow');
                    }

                    return $components;
                }),
                ...$extra,
            ]);
    }

    /**
     * Repeater over a block's embedded items[].
     *
     * @param  list<Component>  $row
     */
    public static function items(string $block, string $label, array $row, string $itemLabelKey = 'label'): Repeater
    {
        return Repeater::make("{$block}.items")
            ->label($label)
            ->schema([
                TextInput::make('key')
                    ->label('Kunci (internal)')
                    ->helperText('ID unik baris ini, huruf kecil tanpa spasi.')
                    ->required(),
                ...$row,
            ])
            ->itemLabel(fn (array $state): ?string => is_array($state[$itemLabelKey] ?? null)
                ? LocaleTabs::itemLabel([$itemLabelKey])($state)
                : ($state[$itemLabelKey] ?? null))
            ->collapsed()
            ->reorderable()
            // Filament keys repeater state by UUID; without array_values the
            // stored list gets non-sequential keys and v-for order drifts.
            ->dehydrateStateUsing(function (array $state): array {
                $rows = [];

                foreach (array_values($state) as $i => $item) {
                    $rows[] = $item + ['sort' => ($i + 1) * 10];
                }

                return $rows;
            })
            ->addActionLabel('Tambah baris');
    }

    /**
     * Optional image for a block (stored on the PageBlock document itself).
     * The form state is not bound to a single record, so the block is
     * resolved explicitly by its full block_key.
     */
    public static function image(string $blockKey, string $label, string $help = ''): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make('image_'.str_replace('.', '_', $blockKey))
            ->label($label)
            ->collection('image')
            ->model(fn (): ?PageBlock => PageBlock::forKey($blockKey))
            ->image()
            ->imageEditor()
            ->maxSize(8192)
            ->helperText($help);
    }

    /** Bilingual text input inside a repeater row. */
    public static function localized(string $attribute, string $label, bool $area = false): Component
    {
        return LocaleGrid::make(fn (string $locale): array => [
            ($area ? Textarea::make("{$attribute}.{$locale}")->rows(3) : TextInput::make("{$attribute}.{$locale}"))
                ->label("{$label} ({$locale})")
                ->required($locale === 'id'),
        ]);
    }

    public static function plain(string $attribute, string $label, bool $required = true): TextInput
    {
        return TextInput::make($attribute)->label($label)->required($required);
    }

    public static function weight(): Select
    {
        return Select::make('value')
            ->label('Bobot tipografi')
            ->options(['md' => 'Sedang', 'lg' => 'Besar', 'xl' => 'Sangat besar'])
            ->required();
    }
}
