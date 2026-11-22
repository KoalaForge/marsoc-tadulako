<?php

namespace App\Filament\Resources\WeaponVariants\Pages;

use App\Filament\Resources\WeaponVariants\WeaponVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeaponVariants extends ListRecords
{
    protected static string $resource = WeaponVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
