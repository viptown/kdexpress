<?php

namespace App\Filament\Resources\SupplierOrdersResource\Pages;

use App\Filament\Resources\SupplierOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierOrders extends EditRecord
{
    protected static string $resource = SupplierOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
