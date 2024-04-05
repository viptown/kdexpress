<?php

namespace App\Filament\Resources\NoSupplierResource\Pages;

use App\Filament\Resources\NoSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNoSupplier extends CreateRecord
{
    protected static string $resource = NoSupplierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
