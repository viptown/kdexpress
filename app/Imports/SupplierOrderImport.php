<?php

namespace App\Imports;

use App\Models\BlStatus;
use App\Models\cbm;
use App\Models\supplier_order;
use App\Models\weight;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierOrderImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return [
            'post' => ['required', 'numeric'],
            'consignee' => ['required', 'max:35'],
            'tel' => ['required', 'max:30'],
            'address' => ['required', 'max:255'],
            'item' => ['required', 'max:255'],
            'qty' => ['required', 'numeric'],
            'size' => ['required', 'numeric'],
            'gross' => ['required', 'numeric'],
            'multy' => ['required', 'max:35'],
            'bl' => ['required', 'max:35'],
            'paytype' => ['required', 'max:10'],
            'origin' => ['required', 'max:25'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'post.required' => '우편번호(post)  :attribute행은 필수 입력 사항 입니다.',
            'post.numeric' => '우편번호(post)  :attribute는 숫자만 입력 가능 합니다.',
            'consignee.required' => '받는분(consignee) :attribute행은 필수 입력 사항 입니다.',
            'consignee.max' => '받는분(consignee)  :attribute행은 35자만 입력 가능 합니다.',
            'tel.required' => '전화번호(tel) :attribute행은 필수 입력 사항 입니다.',
            'tel.max' => '전화번호(tel)  :attribute행은 30자만 입력 가능 합니다.',
            'address.required' => '주소(address) :attribute행은 필수 입력 사항 입니다.',
            'address.max' => '주소(address)  :attribute행은 255자만 입력 가능 합니다.',
            'item.required' => '아이템(item) :attribute행은 필수 입력 사항 입니다.',
            'item.max' => '아이템(item)  :attribute행은 255자만 입력 가능 합니다.',
            'qty.required' => '수량(qty) :attribute행은 필수 입력 사항 입니다.',
            'qty.numeric' => '수량(qty)  :attribute는 숫자만 입력 가능 합니다.',
            'size.required' => '사이즈(size) :attribute행은 필수 입력 사항 입니다.',
            'size.numeric' => '사이즈(size)  :attribute는 숫자만 입력 가능 합니다.',
            'gross.required' => '중량(gross) :attribute행은 필수 입력 사항 입니다.',
            'gross.numeric' => '중량(gross)  :attribute는 숫자만 입력 가능 합니다.',
            'multy.required' => '멀티비엘(multy) :attribute행은 필수 입력 사항 입니다.',
            'multy.max' => '멀티비엘(multy)  :attribute행은 35자만 입력 가능 합니다.',
            'bl.required' => '업체비엘(bl) :attribute행은 필수 입력 사항 입니다.',
            'bl.max' => '업체비엘(bl)  :attribute행은 35자만 입력 가능 합니다.',
            'paytype.required' => '배송구분(paytype) :attribute행은 필수 입력 사항 입니다.',
            'paytype.max' => '배송구분(paytype)  :attribute행은 10자만 입력 가능 합니다.',
            'origin.required' => '파일명(origin) :attribute행은 필수 입력 사항 입니다.',
            'origin.max' => '파일명(origin)  :attribute행은 25자만 입력 가능 합니다.',
        ];
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $supplier_id = session('supplier_id');
        if (!$supplier_id) {
            throw new \Exception('업체 선택해 주세요.');
            return;
        }

        $paytype = $row['paytype'];

        $size = $row['size'];
        //cbm 가격 조회
        $cbmResult = cbm::select('price_express', 'price_regular')
            ->where(function ($query) use ($size) {
                $query->whereNull('start')
                    ->orWhere('start', '<=', $size);
            })
            ->where(function ($query) use ($size) {
                $query->whereNull('end')
                    ->orWhere('end', '>=', $size);
            })
            ->get();

        $cbmPrice = 0;
        if ($cbmResult->isNotEmpty()) {
            $cbmPrice = $cbmResult->first()->price_express;
            if ($paytype == '정기') //정기화물
                $cbmPrice = $cbmResult->first()->price_regular;
            else
                $cbmPrice = $cbmResult->first()->price_express; //택배
        }
        //중량 가격 조회
        $weightResult = weight::select('price_express', 'price_regular')
            ->where(function ($query) use ($size) {
                $query->whereNull('start')
                    ->orWhere('start', '<=', $size);
            })
            ->where(function ($query) use ($size) {
                $query->whereNull('end')
                    ->orWhere('end', '>=', $size);
            })
            ->get();

        $weightPrice = 0;
        if ($weightResult->isNotEmpty()) {
            if ($paytype == '정기') //정기화물
                $weightPrice = $weightResult->first()->price_regular;
            else
                $weightPrice = $weightResult->first()->price_express;
        }

        //cbm 가격과 중량 가격 비교해서 큰것을 운임으로 정한다.
        if ($cbmPrice && $weightPrice) {
            $main_price = ($cbmPrice > $weightPrice) ? $cbmPrice : $weightPrice;
        } else if ($cbmPrice) {
            $main_price = $cbmPrice;
        } else {
            $main_price = 0;
        }

        $bl = $row['bl'];
        if (Str::contains($bl, ',')) {
            $bls = Str::of($bl)->explode(',');
            foreach ($bls as $bl) {
                if (supplier_order::where('supplier_bl', trim($bl))->count() > 0) {
                    throw new \Exception($bl . '중복된 비엘이 있습니다.');
                    return;
                }
                $supplier_order = supplier_order::create([
                    'post' => $row['post'],
                    'consignee' => $row['consignee'],
                    'tel' => $row['tel'],
                    'address' => $row['address'],
                    'item' => $row['item'],
                    'qty' => $row['qty'],
                    'computed_size' => $row['size'],
                    'main_price' => $main_price,
                    'gross_weight' => $row['gross'],
                    'memo' => $row['memo'],
                    'multy_bl' => $row['multy'],
                    'supplier_bl' => trim($bl),
                    'paytype' => $row['paytype'],
                    'origin' => $row['origin'],
                    'ipaddr' => request()->ip(),
                    'supplier_id' => $supplier_id,
                    'user_id' => auth()->id(),
                    'bl_status_id' => BlStatus::where('name', '접수')->first()->id
                ]);
            }

            return $supplier_order;
            //return new supplier_order(...$blArray);
        } else {
            if (supplier_order::where('supplier_bl', trim($bl))->count() > 0) {
                throw new \Exception($bl . ' 중복된 비엘이 있습니다.');
                return;
            }

            return new supplier_order([
                'post' => $row['post'],
                'consignee' => $row['consignee'],
                'tel' => $row['tel'],
                'address' => $row['address'],
                'item' => $row['item'],
                'qty' => $row['qty'],
                'computed_size' => $row['size'],
                'main_price' => $main_price,
                'gross_weight' => $row['gross'],
                'memo' => $row['memo'],
                'multy_bl' => $row['multy'],
                'supplier_bl' => trim($row['bl']),
                'paytype' => $row['paytype'],
                'origin' => $row['origin'],
                'ipaddr' => request()->ip(),
                'supplier_id' => $supplier_id,
                'user_id' => auth()->id(),
                'bl_status_id' => BlStatus::where('name', '접수')->first()->id
            ]);
        }
    }

    // public function onRow(Row $row)
    // {
    //     $rowIndex = $row->getIndex();
    //     $row      = $row->toArray();
    //     $supplier_id = session('supplier_id');
    //     if (!$supplier_id) {
    //         throw new \Exception('업체 선택해 주세요.');
    //         return;
    //     }

    //     supplier_order::where('supplier_bl', $row['bl'])
    //         ->update(['supplier_id' => $supplier_id]);

    // }
}
