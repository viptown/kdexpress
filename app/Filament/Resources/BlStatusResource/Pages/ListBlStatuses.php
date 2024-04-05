<?php

namespace App\Filament\Resources\BlStatusResource\Pages;

use App\Filament\Resources\BlStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlStatuses extends ListRecords
{
    protected static string $resource = BlStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
