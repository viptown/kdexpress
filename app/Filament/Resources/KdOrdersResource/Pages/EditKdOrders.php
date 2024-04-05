<?php

namespace App\Filament\Resources\KdOrdersResource\Pages;

use App\Filament\Resources\KdOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKdOrders extends EditRecord
{
    protected static string $resource = KdOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
