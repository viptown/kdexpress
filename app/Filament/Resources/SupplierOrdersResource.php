<?php

namespace App\Filament\Resources;

use App\Exports\SupplierOrderExport;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\SupplierOrdersResource\Pages;
use App\Imports\SupplierOrderImport;
use App\Imports\MatchOrderImport;
use App\Models\supplier_order;
use App\Models\BlStatus;
use App\Models\User;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;



class SupplierOrdersResource extends Resource
{
    protected static ?string $model = supplier_order::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationLabel = '업체비엘';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Select::make('supplier_id')
                                    ->relationship(name: 'supplier', titleAttribute: 'supplier_name')
                                    ->label('업체명')
                                    ->required()
                                    ->columnSpanFull(),
                                Select::make('bl_status_id')
                                    ->required()
                                    ->relationship(name: 'BlStatus', titleAttribute: 'name')
                                    ->default('접수')
                                    ->label('비엘상태'),
                                Select::make('cargo_location_id')
                                    ->relationship(name: 'CargoLocation', titleAttribute: 'location_name')
                                    ->label('위치'),
                                Select::make('cargo_packing_id')
                                    ->relationship(name: 'CargoPacking', titleAttribute: 'packing_name')
                                    ->label('포장상태'),
                                TextInput::make('post')
                                    ->label('우편번호')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(20)
                                    ->autofocus()
                                    ->placeholder('우편번호'),
                                TextInput::make('consignee')
                                    ->required()
                                    ->label('받는자')
                                    ->maxLength(20)
                                    ->placeholder('받는자 이름'),
                                TextInput::make('tel')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('전화번호'),
                                TextInput::make('address')
                                    ->required()
                                    ->label('주소')
                                    ->maxLength(250)
                                    ->placeholder('주소')
                                    ->columnSpanFull(),
                                TextInput::make('address_details')
                                    ->label('상세주소')
                                    ->maxLength(50)
                                    ->placeholder('상세주소'),
                                TextInput::make('item')
                                    ->label('품목명')
                                    ->maxLength(20)
                                    ->placeholder('품목명'),
                                TextInput::make('qty')
                                    ->required()
                                    ->label('수량')
                                    ->maxLength(20)
                                    ->placeholder('수량'),
                                TextInput::make('computed_size')
                                    ->required()
                                    ->label('사이즈')
                                    ->maxLength(20)
                                    ->placeholder('사이즈'),
                                TextInput::make('main_price')
                                    ->label('가격')
                                    ->maxLength(20)
                                    ->placeholder('가격'),
                                TextInput::make('last_price')
                                    ->label('최종가격')
                                    ->maxLength(20)
                                    ->placeholder('최종가격'),
                                TextInput::make('gross_weight')
                                    ->required()
                                    ->label('중량')
                                    ->maxLength(20)
                                    ->placeholder('중량'),
                                TextInput::make('last_weight')
                                    ->label('최종중량')
                                    ->maxLength(20)
                                    ->placeholder('최종중량'),
                                Textarea::make('memo')
                                    ->label('메모')
                                    ->placeholder('메모')
                                    ->autosize()
                                    ->columnSpanFull(),

                                TextInput::make('multy_bl')
                                    ->required()
                                    ->label('멀티비엘')
                                    ->maxLength(20)
                                    ->placeholder('멀티비엘'),
                                TextInput::make('supplier_bl')
                                    ->required()
                                    ->label('업체비엘')
                                    ->maxLength(20)
                                    ->placeholder('업체비엘'),
                                TextInput::make('kd_bl')
                                    ->label('경동비엘')
                                    ->maxLength(20)
                                    ->placeholder('경동비엘'),
                                TextInput::make('paytype')
                                    ->label('배송구분')
                                    ->maxLength(15)
                                    ->placeholder('배송구분'),
                                TextInput::make('origin')
                                    ->required()
                                    ->label('파일명')
                                    ->maxLength(20)
                                    ->placeholder('파일명'),
                            ])->columns(3)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $export_file_name = date('Y-m-d H:i:s') . "_Orders.xlsx";
        return $table
            ->headerActions([

                // ImportAction::make()
                //     ->importer(SupplierOrderImporter::class)
                ImportAction::make('importOrders')
                    ->visible(auth()->user()->can('importAdminEditor', User::class))
                    ->label('업체비엘 업로드')
                    ->color('info')
                    ->form([
                        Select::make('supplier_id')
                            ->label('업체명')
                            ->relationship(name: 'supplier', titleAttribute: 'supplier_name'),

                        FileUpload::make('attachement'),
                    ])
                    ->action(function (array $data) {

                        if (array_key_exists('attachement', $data) && array_key_exists('supplier_id', $data)) {
                            $file_name = $data["attachement"];
                            $supplier_id = $data["supplier_id"];
                            session(['supplier_id' => $supplier_id]);
                        } else {
                            $file_name = "";
                            //request()->session()->flush();
                        }

                        // $data is an array which consists of all the form data 
                        $file = public_path("storage/" . $file_name);
                        //////$file = storage_path('app/public' . $file_name);

                        try {
                            Excel::import(new SupplierOrderImport, $file);
                            Notification::make()
                                ->success()
                                ->title('Orders Imported')
                                ->body('Orders data imported successfully.')
                                ->send();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Orders Upload Error Occured!!!')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                Action::make('DownOrders')
                    ->visible(auth()->user()->can('importAdminEditor', User::class))
                    ->label('경동라벨 다운로드')
                    ->action(fn () => Excel::download(new SupplierOrderExport, $export_file_name)),

                ImportAction::make('matchingOrders')
                    ->visible(auth()->user()->can('importAdminEditor', User::class))
                    ->label('경동-업체비엘 매칭')
                    ->color('danger')
                    ->form([
                        FileUpload::make('attachMatching'),
                    ])
                    ->action(function (array $data) {

                        if (array_key_exists('attachMatching', $data)) {
                            $file_name = $data["attachMatching"];
                        } else {
                            $file_name = "";
                            //request()->session()->flush();
                        }

                        // $data is an array which consists of all the form data 
                        $file = public_path("storage/" . $file_name);
                        //////$file = storage_path('app/public' . $file_name);

                        try {
                            Excel::import(new MatchOrderImport, $file);
                            Notification::make()
                                ->success()
                                ->title('Matching File Imported')
                                ->body('매칭 데이터가 성공적으로 업로드 되였습니다.')
                                ->send();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Matching Upload Error Occured!!!')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

            ])
            ->columns([
                Tables\Columns\TextColumn::make('supplier.supplier_name')
                    ->label('업체명')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin')->label('파일명')->sortable(),
                Tables\Columns\TextColumn::make('post')->label('우편번호'),
                Tables\Columns\TextColumn::make('consignee')->label('받는자')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tel')->label('전화번호')->searchable(),
                Tables\Columns\TextColumn::make('address')->label('주소')->limit(35)->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address_details')->label('상세주소')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item')->label('품목명')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('qty')->label('수량'),
                Tables\Columns\TextColumn::make('computed_size')->label('사이즈')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('main_price')->label('가격'),
                Tables\Columns\TextColumn::make('gross_weight')->label('중량'),
                Tables\Columns\TextColumn::make('memo')->label('메모'),
                Tables\Columns\TextColumn::make('BlStatus.name')->label('비엘상태'),
                Tables\Columns\TextColumn::make('arrival_date')->label('입고일')->searchable(),
                Tables\Columns\TextColumn::make('departure_date')->label('출고일')->searchable(),
                Tables\Columns\TextColumn::make('multy_bl')->label('멀티비엘')->searchable(),
                Tables\Columns\TextColumn::make('supplier_bl')->label('업체비엘')->searchable(),
                Tables\Columns\TextColumn::make('paytype')->label('배송구분'),
                Tables\Columns\TextColumn::make('ipaddr')->label('아이피')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')->label('사용자')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->can('onlyApiUser', User::class)) {
                    $query->where('supplier_id', auth()->user()->supplier_id);
                }
            })
            ->filters([
                SelectFilter::make('업체명')
                    ->relationship(name: 'supplier', titleAttribute: 'supplier_name'),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('생성일 부터'),
                        Forms\Components\DatePicker::make('created_until')->label('생성일 까지'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Created from ' . Carbon::parse($data['from'])->toFormattedDateString();
                        }

                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Created until ' . Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })->columnSpan(2)->columns(2),
            ])
            //], layout: FiltersLayout::AboveContent)->filtersFormColumns(2)

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (supplier_order $record): bool => $record->bl_status_id === BlStatus::where('name', '접수')->first()->id,
            )
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierOrders::route('/'),
            'create' => Pages\CreateSupplierOrders::route('/create'),
            'edit' => Pages\EditSupplierOrders::route('/{record}/edit'),
        ];
    }
}
