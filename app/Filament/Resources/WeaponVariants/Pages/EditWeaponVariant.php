<?php

namespace App\Filament\Resources\WeaponVariants\Pages;

use App\Filament\Resources\WeaponVariants\WeaponVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeaponVariant extends EditRecord
{
    protected static string $resource = WeaponVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
