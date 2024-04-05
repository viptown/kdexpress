<?php

namespace App\Filament\Imports;

use App\Models\supplier;
use App\Models\supplier_order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;

class SupplierOrderImporter extends Importer
{
    protected static ?string $model = supplier_order::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('post')->label('우편번호'),
            ImportColumn::make('consignee')->label('받는자'),
            ImportColumn::make('tel')->label('전화번호'),
            ImportColumn::make('address')->label('주소'),
            ImportColumn::make('address_details')->label('상세주소'),
            ImportColumn::make('item')->label('item'),
            ImportColumn::make('qty')->label('수량'),
            ImportColumn::make('computed_size')->label('사이즈'),
            ImportColumn::make('main_price')->label('가격'),
            ImportColumn::make('gross_weight')->label('중량'),
            ImportColumn::make('memo')->label('메모'),
            ImportColumn::make('multy_bl')->label('멀티비엘'),
            ImportColumn::make('supplier_bl')->label('업체비엘'),
            ImportColumn::make('paytype')->label('착불/선불'),
            ImportColumn::make('origin')->label('파일명'),

            // ImportColumn::make('post')->label('post')->requiredMapping()->numeric()->rules(['required', 'max:10']),
            // ImportColumn::make('consignee')->label('consignee')->requiredMapping()->rules(['required', 'max:25']),
            // ImportColumn::make('tel')->label('tel')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('address')->label('address')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('address_details')->label('address_details')->requiredMapping(),
            // ImportColumn::make('item')->label('item')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('qty')->label('qty')->requiredMapping()->integer()->rules(['required', 'max:20']),
            // ImportColumn::make('computed_size')->label('computed_size')->requiredMapping()->numeric()->rules(['required', 'max:20']),
            // ImportColumn::make('main_price')->label('main_price')->requiredMapping()->numeric()->rules(['required', 'max:20']),
            // ImportColumn::make('gross_weight')->label('gross_weight')->requiredMapping()->numeric()->rules(['required', 'max:20']),
            // ImportColumn::make('memo')->label('memo')->requiredMapping(),
            // ImportColumn::make('multy_bl')->label('multy_bl')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('supplier_bl')->label('supplier_bl')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('paytype')->label('paytype')->requiredMapping()->rules(['required', 'max:20']),
            // ImportColumn::make('origin')->label('origin')->requiredMapping()->rules(['required', 'max:20']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('supplier_id')
                ->label('업체선택')
                ->relationship(name: 'supplier', titleAttribute: 'supplier_name')
        ];
    }

    public function resolveRecord(): ?supplier_order
    {
        // return SupplierOrder::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new supplier_order();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your supplier order import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public function getValidationMessages(): array
    {
        return [
            'name.required' => 'The name column must not be empty.',
        ];
    }
    // protected function beforeValidate(): void
    // {
    //     dd('beforeValidate');
    // }

    // protected function afterValidate(): void
    // {
    //     // Runs after the CSV data for a row is validated.
    //     dd('afterValidate');
    // }

    // protected function beforeFill(): void
    // {
    //     // Runs before the validated CSV data for a row is filled into a model instance
    //     dd('beforeFill');
    // }

    // protected function afterFill(): void
    // {
    //     // Runs after the validated CSV data for a row is filled into a model instance.
    //     dd('afterFill');
    // }

    // protected function beforeSave(): void
    // {
    //     // Runs before a record is saved to the database.
    //     dd('beforeSave');
    // }

    // protected function beforeCreate(): void
    // {
    //     // Similar to `beforeSave()`, but only runs when creating a new record.
    //     dd('beforeCreate');
    // }

    // protected function beforeUpdate(): void
    // {
    //     // Similar to `beforeSave()`, but only runs when updating an existing record.
    //     dd('beforeUpdate');
    // }

    // protected function afterSave(): void
    // {
    //     // Runs after a record is saved to the database.
    //     dd('afterSave');
    // }

    // protected function afterCreate(): void
    // {
    //     // Similar to `afterSave()`, but only runs when creating a new record.
    //     dd('afterCreate');
    // }

    // protected function afterUpdate(): void
    // {
    //     // Similar to `afterSave()`, but only runs when updating an existing record.
    //     dd('afterUpdate');
    // }
}
