<?php

namespace App\Filament\Resources\CargoPackingResource\Pages;

use App\Filament\Resources\CargoPackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCargoPacking extends EditRecord
{
    protected static string $resource = CargoPackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
