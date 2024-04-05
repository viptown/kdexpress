<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CbmResource\Pages;
use App\Filament\Resources\CbmResource\RelationManagers;
use App\Models\Cbm;
use App\Models\supplier;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CbmResource extends Resource
{
    protected static ?string $model = Cbm::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?string $navigationLabel = 'Cbm';

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

                                TextInput::make('start')
                                    ->required()
                                    ->label('시작')
                                    ->numeric()
                                    ->placeholder('시작'),
                                TextInput::make('end')
                                    ->required()
                                    ->label('끝')
                                    ->numeric()
                                    ->placeholder('끝'),
                                TextInput::make('price_express')
                                    ->required()
                                    ->label('택배가격')
                                    ->numeric()
                                    ->placeholder('택배 가격'),
                                TextInput::make('price_regular')
                                    ->required()
                                    ->label('정규가격')
                                    ->numeric()
                                    ->placeholder('정규 가격'),
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start')->label('시작'),
                Tables\Columns\TextColumn::make('end')->label('끝'),
                Tables\Columns\TextColumn::make('price_express')->label('택배가격'),
                Tables\Columns\TextColumn::make('price_regular')->label('정규가격'),
                Tables\Columns\TextColumn::make('supplier.supplier_name')->label('업체명')
                    ->sortable(),
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
            'index' => Pages\ListCbms::route('/'),
            'create' => Pages\CreateCbm::route('/create'),
            'edit' => Pages\EditCbm::route('/{record}/edit'),
        ];
    }
}
