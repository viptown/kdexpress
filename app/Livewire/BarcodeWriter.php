<?php

namespace App\Livewire;

use App\Models\BlStatus;
use App\Models\cargo_location;
use App\Models\cargo_packing;
use App\Models\no_supplier;
use App\Models\supplier_order;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class BarcodeWriter extends Component
{
    #[Validate('required|min:12')]
    public $barcode;

    #[Validate('required')]
    public $location;

    #[Validate('required')]
    public $packing;

    #[Validate('required')]
    public $cargoPrice;

    #[Validate('required')]
    public $weight;

    public $supplier;
    public $supplierSW = false;

    public function searchOrder()
    {
        if (Str::length($this->barcode) >= 12) {
            $supplierOrder = supplier_order::where('supplier_bl', $this->barcode)->first();
            if ($supplierOrder?->exists()) {
                if ($supplierOrder->paytype == '착택' || $supplierOrder->paytype == '착불') {
                    if ($supplierOrder->main_price > 0) {
                        if ($supplierOrder->gross_weight < 50) {
                            $this->cargoPrice = $supplierOrder->main_price + 1000;
                        } else if ($supplierOrder->gross_weight >= 50) {
                            $this->cargoPrice = $supplierOrder->main_price + 2000;
                        }
                    }
                } else {
                    $this->cargoPrice = $supplierOrder->main_price;
                }

                if ($supplierOrder->gross_weight > 0) {
                    $this->weight = $supplierOrder->gross_weight;
                }
            } else {
                $this->supplierSW = true;
            }
        }
    }

    public function storeOrder()
    {


        if (!$this->barcode) {
            session()->flash('message', '업체비엘을 입력해 주셔야 합니다.');
            return;
        }

        if (!$this->location) {
            session()->flash('message', $this->location . '위치를 선택해 주셔야 합니다.');
            return;
        }

        if (!$this->packing) {
            session()->flash('message', '포장을 선택해 주셔야 합니다.');
            return;
        }

        $this->validate();

        $supplierOrder = supplier_order::where('supplier_bl', $this->barcode)->first();
        if (!$supplierOrder?->exists()) {
            if (!$this->supplier) {
                session()->flash('message', '업체명을 선택해주세요');
                return;
            }

            no_supplier::create([
                'supplier_id' => $this->supplier,
                'supplier_bl' => $this->barcode,
                'cargo_location_id' => $this->location,
                'user_id' => auth()->id(),
            ]);

            $this->supplierSW = false;
            session()->flash('message', 'No BL 입력성공');
            return;
        }

        //모바일로 바코드 입고시 오직 비엘상태가 '접수'인 상태만 입고 할수 있다.
        // if ($supplierOrder->bl_status_id != BlStatus::where('name', '접수')->first()->id) {
        //     session()->flash('message', '오직 [접수]된 비엘만 입고 가능 합니다.');
        //     return;
        // }

        $bl_status_id_1 = ''; //접수 
        $bl_status_id_2 = ''; //입고
        $bl_status_id_3 = ''; //출고
        $bl_status_id_4 = ''; //자차
        $bl_status_id_5 = ''; //취소

        $blstatuses = BlStatus::all();
        foreach ($blstatuses as $blstatus) {
            switch ($blstatus->name) {
                case '접수':
                    $bl_status_id_1 = $blstatus->id;
                    break;
                case '입고':
                    $bl_status_id_2 = $blstatus->id;
                    break;
                case '출고':
                    $bl_status_id_3 = $blstatus->id;
                    break;
                case '자차':
                    $bl_status_id_4 = $blstatus->id;
                    break;
                case '취소':
                    $bl_status_id_5 = $blstatus->id;
                    break;
            }
        }

        switch ($supplierOrder->bl_status_id) {
            case $bl_status_id_1:

                supplier_order::where('supplier_bl', $this->barcode)
                    ->update([
                        'arrival_date' => Carbon::now(),
                        'cargo_packing_id' => $this->packing,
                        'cargo_location_id' => $this->location,
                        'bl_status_id' => BlStatus::where('name', '입고')->first()->id,
                        'last_price' => $this->cargoPrice,
                        'last_weight' => $this->weight,
                    ]);

                session()->flash('message', '입력성공');

                $this->reset(['barcode', 'cargoPrice', 'weight']);

                break;
            case $bl_status_id_2:
                session()->flash('message', '이미 입고 된 비엘 입니다.');
                break;
            case $bl_status_id_3:
                session()->flash('message', '이미 출고 된 비엘 입니다.');
                break;
            case $bl_status_id_4:
                session()->flash('message', '자차 건 비엘 입니다.');
                break;
            case $bl_status_id_5:
                session()->flash('message', '취소 건 비엘 입니다.');
                break;
        }
    }

    public function render()
    {
        //dd(supplier_order::where('bl_status_id', '4')->count());

        $this->searchOrder();

        return view('livewire.barcode-writer');
    }
}
