<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityCategories;

use App\Enums\NavigationGroup;
use App\Filament\Resources\ActivityCategories\Pages\CreateActivityCategory;
use App\Filament\Resources\ActivityCategories\Pages\EditActivityCategory;
use App\Filament\Resources\ActivityCategories\Pages\ListActivityCategories;
use App\Filament\Resources\ActivityCategories\Schemas\ActivityCategoryForm;
use App\Filament\Resources\ActivityCategories\Tables\ActivityCategoriesTable;
use App\Models\ActivityCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ActivityCategoryResource extends Resource
{
    protected static ?string $model = ActivityCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Pengaturan;

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return ActivityCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityCategoriesTable::configure($table);
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
            'index' => ListActivityCategories::route('/'),
            'create' => CreateActivityCategory::route('/create'),
            'edit' => EditActivityCategory::route('/{record}/edit'),
        ];
    }
}
