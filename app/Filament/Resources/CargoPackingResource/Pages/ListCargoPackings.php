<?php

namespace App\Filament\Resources\CargoPackingResource\Pages;

use App\Filament\Resources\CargoPackingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCargoPackings extends ListRecords
{
    protected static string $resource = CargoPackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
