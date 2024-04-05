<?php

namespace App\Exports;

use App\Models\BlStatus;
use App\Models\supplier_order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class SupplierOrderExport implements WithMapping, WithHeadings, FromQuery
{
    use Exportable;

    public function query()
    {
        return supplier_order::with(['BlStatus' => function ($query) {
            $query->where('name', '입고');
        }])->where('bl_status_id', BlStatus::where('name', '입고')->first()->id);

        //return supplier_order::where('bl_status_id', BlStatus::where('name', '입고')->first()->id);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {

    //     return supplier_order::whereHas('OrderStatus', function ($q) {
    //         $q->where('bl_status', '=', 2);
    //     })->get();
    // }

    public function headings(): array
    {
        return  [
            '우편번호',
            '도착영업소',
            '받는 사람',
            '전화번호',
            '주소',
            '상세주소',
            '품목명',
            '수량',
            '포장상태',
            '개별단가',
            '배송구분',
            '운임',
            '별도운임',
            '기타운임',
            '메모1',
            '메모2',
            '메모3',
            '메모4',
            '메모5',
            '메모6',
            '메모7',
            '메모8',
            '메모9',
            '메모10',
            '메모11',
            '메모12',
            '메모13',
            '메모14',
            '메모15',
        ];
    }

    public function map($SupplierOrder): array
    {
        return [
            $SupplierOrder->post,
            '',
            $SupplierOrder->consignee,
            $SupplierOrder->tel,
            $SupplierOrder->address,
            $SupplierOrder->address_details,
            $SupplierOrder->item,
            $SupplierOrder->qty,
            $SupplierOrder->CargoPacking?->packing_name,
            '100',
            $SupplierOrder->paytype,
            $SupplierOrder->main_price,
            '',
            '100',
            $SupplierOrder->multy_bl,
            '화물위치:' . $SupplierOrder->CargoLocation?->location_name,
            $SupplierOrder->origin,
            '총수량:' . $SupplierOrder::groupBy('multy_bl')->count(),
            $SupplierOrder->supplier->supplier_name . ':' . $SupplierOrder->supplier_bl,
            $SupplierOrder->memo,
            '', '', '', '', '', '', '', '',
        ];
    }
}
