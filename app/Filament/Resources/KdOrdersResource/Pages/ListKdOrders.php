<?php

namespace App\Filament\Resources\KdOrdersResource\Pages;

use App\Filament\Resources\KdOrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKdOrders extends ListRecords
{
    protected static string $resource = KdOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
