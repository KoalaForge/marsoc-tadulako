<?php

declare(strict_types=1);

namespace App\Filament\Resources\PublicationCategories;

use App\Enums\NavigationGroup;
use App\Filament\Resources\PublicationCategories\Pages\CreatePublicationCategory;
use App\Filament\Resources\PublicationCategories\Pages\EditPublicationCategory;
use App\Filament\Resources\PublicationCategories\Pages\ListPublicationCategories;
use App\Filament\Resources\PublicationCategories\Schemas\PublicationCategoryForm;
use App\Filament\Resources\PublicationCategories\Tables\PublicationCategoriesTable;
use App\Models\PublicationCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PublicationCategoryResource extends Resource
{
    protected static ?string $model = PublicationCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Pengaturan;

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return PublicationCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicationCategories::route('/'),
            'create' => CreatePublicationCategory::route('/create'),
            'edit' => EditPublicationCategory::route('/{record}/edit'),
        ];
    }
}
