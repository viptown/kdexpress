<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CargoLocationResource\Pages;
use App\Filament\Resources\CargoLocationResource\RelationManagers;
use App\Models\cargo_location;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CargoLocationResource extends Resource
{
    protected static ?string $model = cargo_location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?string $navigationLabel = '위치';
    protected static ?string $modelLabel = 'location';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('location_name')
                                    ->required()
                                    ->label('위치명')
                                    ->placeholder('위치명을 입력해 주세요.'),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location_name')->label('위치명'),
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
            'index' => Pages\ListCargoLocations::route('/'),
            'create' => Pages\CreateCargoLocation::route('/create'),
            'edit' => Pages\EditCargoLocation::route('/{record}/edit'),
        ];
    }
}
