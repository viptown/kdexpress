<?php

namespace App\Exports;

use App\Models\no_supplier;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class NoSupplierExport implements WithMapping, WithHeadings, FromQuery
{
    use Exportable;

    public function query()
    {
        return no_supplier::query();
        //return no_supplier::with(['supplier.supplier_name', 'cargo_location.location_name', 'user.name'])->get();
        //return supplier_order::where('bl_status_id', BlStatus::where('name', '입고')->first()->id);
    }

    public function headings(): array
    {
        return  [
            '업체명',
            '업체비엘',
            '위치',
            '사용자',
            '등록일',
        ];
    }

    public function map($noSupplier): array
    {
        return [
            $noSupplier->supplier->supplier_name,
            $noSupplier->supplier_bl,
            $noSupplier->cargo_location->location_name,
            $noSupplier->user->name,
            $noSupplier->created_at,
        ];
    }
}
