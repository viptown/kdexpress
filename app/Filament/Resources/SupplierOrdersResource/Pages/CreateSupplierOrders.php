<?php

namespace App\Filament\Resources\SupplierOrdersResource\Pages;

use App\Filament\Resources\SupplierOrdersResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;


class CreateSupplierOrders extends CreateRecord
{
    protected static string $resource = SupplierOrdersResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['ipaddr'] = request()->ip();
        $data['created_at'] = Carbon::now()->timestamp;
        $data['updated_at'] = Carbon::now()->timestamp;

        return $data;
    }
}
