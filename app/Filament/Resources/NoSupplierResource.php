<?php

namespace App\Filament\Resources;

use App\Exports\NoSupplierExport;
use App\Filament\Resources\NoSupplierResource\Pages;
use App\Filament\Resources\NoSupplierResource\RelationManagers;
use App\Models\no_supplier;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

class NoSupplierResource extends Resource
{
    protected static ?string $model = no_supplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'NoData';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('supplier_bl')
                                    ->label('업체비엘')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(30)
                                    ->autofocus()
                                    ->placeholder('업체비엘을 입력해주세요.'),
                                Select::make('location_id')
                                    ->relationship(name: 'cargo_location', titleAttribute: 'location_name')
                                    ->label('위치명')
                                    ->required()
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $export_file_name = date('Y-m-d H:i:s') . "_nodata.xlsx";
        return $table
            ->headerActions([
                Action::make('DownOrders')
                    ->label('NO DATA 다운로드')
                    ->action(fn () => Excel::download(new NoSupplierExport, $export_file_name)),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('supplier.supplier_name')->label('업체명'),
                Tables\Columns\TextColumn::make('supplier_bl')->label('업체비엘'),
                Tables\Columns\TextColumn::make('cargo_location.location_name')->label('위치'),
                Tables\Columns\TextColumn::make('user.name')->label('사용자'),
                Tables\Columns\TextColumn::make('created_at')->label('등록일'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->can('onlyApiUser', User::class)) {
                    $query->where('supplier_id', auth()->user()->supplier_id);
                }
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNoSuppliers::route('/'),
            'create' => Pages\CreateNoSupplier::route('/create'),
            'edit' => Pages\EditNoSupplier::route('/{record}/edit'),
        ];
    }
}
