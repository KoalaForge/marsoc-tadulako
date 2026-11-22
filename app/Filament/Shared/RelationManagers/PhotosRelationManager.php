<?php

declare(strict_types=1);

namespace App\Filament\Shared\RelationManagers;

use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Per-image metadata for the `photos` media collection. Spatie's own
 * uploader handles files + reorder; this relation manager — over the
 * model's own `media()` morph (from InteractsWithMedia) — handles the
 * bilingual caption/alt + required credit that the uploader has no UI
 * for.
 *
 * Cross-connection caveat: `media()` is a MorphMany from a Mongo model
 * to App\Models\Media on the `sqlite_media` connection. A plain
 * where-filtered listing works fine; never wrap this in whereHas() or
 * withCount() from the Mongo side — those compile to a cross-database
 * subquery and fail.
 */
class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Foto';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("custom_properties.alt.{$locale}")
                    ->label("Alt ({$locale})")
                    ->maxLength(125),
                Textarea::make("custom_properties.caption.{$locale}")
                    ->label("Caption ({$locale})")
                    ->rows(2),
            ]),
            TextInput::make('custom_properties.credit')
                ->label('Kredit / Sumber')
                ->helperText('Wajib untuk foto referensi DoD/USMC — lihat PRODUCT.md disiplin referensi.')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('collection_name', 'photos'))
            ->defaultSort('order_column')
            ->columns([
                // Plain ImageColumn, not SpatieMediaLibraryImageColumn: the rows
                // here ARE Media records, and the Spatie column expects an owner
                // model with a `media` relation (it eager-loads it and crashes).
                ImageColumn::make('preview')
                    ->label('')
                    ->state(fn (Media $record): string => $record->hasGeneratedConversion('thumb')
                        ? $record->getUrl('thumb')
                        : $record->getUrl()),
                TextColumn::make('custom_properties.alt.id')
                    ->label('Alt (ID)')
                    ->placeholder('—'),
                TextColumn::make('custom_properties.caption.id')
                    ->label('Caption (ID)')
                    ->limit(50),
                TextColumn::make('custom_properties.credit')
                    ->label('Kredit')
                    ->placeholder('—'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
