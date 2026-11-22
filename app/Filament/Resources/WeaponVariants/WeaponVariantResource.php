<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeaponVariants;

use App\Enums\NavigationGroup;
use App\Filament\Resources\WeaponVariants\Pages\CreateWeaponVariant;
use App\Filament\Resources\WeaponVariants\Pages\EditWeaponVariant;
use App\Filament\Resources\WeaponVariants\Pages\ListWeaponVariants;
use App\Filament\Resources\WeaponVariants\Schemas\WeaponVariantForm;
use App\Filament\Resources\WeaponVariants\Tables\WeaponVariantsTable;
use App\Filament\Shared\RelationManagers\PhotosRelationManager;
use App\Models\WeaponVariant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WeaponVariantResource extends Resource
{
    protected static ?string $model = WeaponVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::ReferensiImpresi;

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return WeaponVariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeaponVariantsTable::configure($table);
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
            'index' => ListWeaponVariants::route('/'),
            'create' => CreateWeaponVariant::route('/create'),
            'edit' => EditWeaponVariant::route('/{record}/edit'),
        ];
    }
}
