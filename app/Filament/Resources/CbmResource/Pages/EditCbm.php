<?php

namespace App\Filament\Resources\CbmResource\Pages;

use App\Filament\Resources\CbmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCbm extends EditRecord
{
    protected static string $resource = CbmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
