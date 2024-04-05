<?php

namespace App\Filament\Resources\CargoLocationResource\Pages;

use App\Filament\Resources\CargoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCargoLocation extends EditRecord
{
    protected static string $resource = CargoLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
