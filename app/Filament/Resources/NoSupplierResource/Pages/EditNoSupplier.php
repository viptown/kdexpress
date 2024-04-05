<?php

namespace App\Filament\Resources\NoSupplierResource\Pages;

use App\Filament\Resources\NoSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNoSupplier extends EditRecord
{
    protected static string $resource = NoSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
