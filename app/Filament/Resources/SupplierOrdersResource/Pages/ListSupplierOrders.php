<?php

namespace App\Filament\Resources\SupplierOrdersResource\Pages;

use App\Filament\Resources\SupplierOrdersResource;
use App\Models\supplier_order;
use Filament\Actions;
use Filament\Actions\ImportAction;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSupplierOrders extends ListRecords
{
    protected static string $resource = SupplierOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'All' => Tab::make(),
    //         '이번주' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subWeek()))
    //             ->badge(fn () => supplier_order::where('created_at', '>=', now()->subWeek())->count()),
    //         '이번달' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subMonth()))
    //             ->badge(fn () => supplier_order::where('created_at', '>=', now()->subMonth())->count()),
    //         '올해' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subYear()))
    //             ->badge(fn () => supplier_order::where('created_at', '>=', now()->subYear())->count()),
    //     ];
    // }
}
