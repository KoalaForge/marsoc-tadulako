<?php

declare(strict_types=1);

namespace App\Filament\Resources\Eras;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Eras\Pages\CreateEra;
use App\Filament\Resources\Eras\Pages\EditEra;
use App\Filament\Resources\Eras\Pages\ListEras;
use App\Filament\Resources\Eras\Schemas\EraForm;
use App\Filament\Resources\Eras\Tables\ErasTable;
use App\Filament\Shared\RelationManagers\PhotosRelationManager;
use App\Models\Era;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EraResource extends Resource
{
    protected static ?string $model = Era::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::ReferensiImpresi;

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return EraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEras::route('/'),
            'create' => CreateEra::route('/create'),
            'edit' => EditEra::route('/{record}/edit'),
        ];
    }
}
