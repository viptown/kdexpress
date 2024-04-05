<?php

namespace App\Filament\Resources\CbmResource\Pages;

use App\Filament\Resources\CbmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCbms extends ListRecords
{
    protected static string $resource = CbmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
