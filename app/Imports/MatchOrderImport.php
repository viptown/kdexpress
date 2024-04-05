<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use App\Models\BlStatus;
use App\Models\supplier_order;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ValidatorsValidationException;
use RuntimeException;

class MatchOrderImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return [
            'shipper' => ['required', 'max:50'],
            'arrival' => ['required', 'max:50'],
            'bl' => ['required', 'max:30'],
            'memo' => ['required', 'max:255'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'shipper.required' => '보내는분(shipper)  :attribute행은 필수 입력 사항 입니다.',
            'shipper.max' => '보내는분(shipper)  :attribute행은 50자만 입력 가능 합니다.',
            'arrival.required' => '도착지(arrival) :attribute행은 필수 입력 사항 입니다.',
            'arrival.max' => '도착지(arrival)  :attribute행은 50자만 입력 가능 합니다.',
            'bl.required' => '비엘(bl) :attribute행은 필수 입력 사항 입니다.',
            'bl.max' => '비엘(bl)  :attribute행은 30자만 입력 가능 합니다.',
            'memo.required' => '메모(memo) :attribute행은 필수 입력 사항 입니다.',
            'memo.max' => '메모(memo)  :attribute행은 255자만 입력 가능 합니다.',
        ];
    }

    public function collection(Collection $rows)
    {
        //dd($row);
        // dd($rows);
        //dd($rows->count());
        if ($rows->count() <= 0) {
            throw new Exception('Excel파일에 문제가 있습니다. 확인후 다시 업로드 해주세요.');
        }

        try {

            foreach ($rows as $index => $row) {
                // 행별 유효성 검사 규칙을 적용
                $validator = Validator::make($row->toArray(), $this->rules(), $this->customValidationMessages());

                // 유효성 검사 실패 시 예외 처리
                if ($validator->fails()) {
                    $errors = $validator->errors()->all();
                    throw ValidationException::withMessages(['row' => $index + 2, 'errors' => $errors]);
                }

                $biz_shipper = $row['shipper'];
                $biz_arrival = $row['arrival'];
                $kd_bl = $row['bl']; //경동비엘
                $memo = $row['memo'];

                if (Str::contains($memo, '화물위치')) {
                    $memoArr = Str::of($memo)->explode('화물위치');
                    $supplier_bl = trim($memoArr[0]);
                }

                if (!$supplier_bl) {
                    throw new Exception('업체비엘이 존재하지 않습니다.');
                }

                supplier_order::where('supplier_bl', $supplier_bl)
                    ->update([
                        'biz_shipper' => $biz_shipper,
                        'biz_arrival' => $biz_arrival,
                        'kd_bl' => $kd_bl,
                        'bl_status_id' => BlStatus::where('name', '출고')->first()->id,
                        'departure_date' => Carbon::now()
                    ]);
            }
        } catch (ValidationException $e) {
            // 유효성 검사 실패 시 오류 처리
            $failures = $e->validator->getMessageBag()->all();
            return Redirect::back()->withErrors(['excel_file' => $failures])->withInput();
        } catch (Exception $e) {
            // 기타 예외 처리
            return Redirect::back()->withErrors(['excel_file' => $e->getMessage()])->withInput();
        }
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {
    //     //dd($row);
    //     $biz_shipper = $row['shipper'];
    //     $biz_arrival = $row['arrival'];
    //     $kd_bl = $row['bl']; //경동비엘
    //     $memo = $row['memo'];
    //     if (Str::contains($memo, '화물위치')) {
    //         $memoArr = Str::of($memo)->explode('화물위치');
    //         $supplier_bl = trim($memoArr[0]);
    //     }

    //     if (!$supplier_bl) {
    //         throw new Exception('업체비엘이 존재하지 않습니다.');
    //     }

    //     $supplier_order = supplier_order::where('supplier_bl', $supplier_bl)
    //         ->update([
    //             'biz_shipper' => $biz_shipper,
    //             'biz_arrival' => $biz_arrival,
    //             'kd_bl' => $kd_bl,
    //             'bl_status_id' => BlStatus::where('name', '출고')->first()->id,
    //             'departure_date' => Carbon::now()
    //         ]);

    //     return $supplier_order;
    // }
}
