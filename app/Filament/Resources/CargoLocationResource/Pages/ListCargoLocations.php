<?php

namespace App\Filament\Resources\CargoLocationResource\Pages;

use App\Filament\Resources\CargoLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCargoLocations extends ListRecords
{
    protected static string $resource = CargoLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
