<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationLabel = '업체명';
    // protected static ?string $modelLabel = '업체명';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('supplier_name')
                                    ->label('업체명')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(50)
                                    ->autofocus()
                                    ->placeholder('업체명을 입력해주세요.'),
                                TextInput::make('tel')
                                    ->label('전화번호')
                                    ->maxLength(20)
                                    ->placeholder('전화번호를 입력해주세요.'),
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(20)
                                    ->label('이메일')
                                    ->placeholder('이메일 입력해주세요.'),
                                TextInput::make('api_supplier_id')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('api supplier Id 입력해주세요.'),
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier_name'),
                Tables\Columns\TextColumn::make('tel'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('api_supplier_id'),
            ])
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
