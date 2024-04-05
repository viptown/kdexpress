<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CargoPackingResource\Pages;
use App\Filament\Resources\CargoPackingResource\RelationManagers;
use App\Models\cargo_packing;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CargoPackingResource extends Resource
{
    protected static ?string $model = cargo_packing::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationLabel = '포장상태';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?int $navigationSort = 3;

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
                                Select::make('is_visible')
                                    ->options([
                                        '1' => '보이기',
                                        '0' => '숨기기',
                                    ])
                                    ->label('보이기/숨기기')
                                    ->required(),

                                TextInput::make('packing_name')
                                    ->required()
                                    ->label('포장지')
                                    ->placeholder('포장지 입력해 주십시요.'),

                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('packing_name')->label('포장상태'),
                Tables\Columns\TextColumn::make('is_visible')->label('숨기기'),
                Tables\Columns\TextColumn::make('supplier.supplier_name')->label('업체명')
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
            'index' => Pages\ListCargoPackings::route('/'),
            'create' => Pages\CreateCargoPacking::route('/create'),
            'edit' => Pages\EditCargoPacking::route('/{record}/edit'),
        ];
    }
}
