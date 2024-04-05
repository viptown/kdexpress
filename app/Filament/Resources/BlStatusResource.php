<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlStatusResource\Pages;
use App\Filament\Resources\BlStatusResource\RelationManagers;
use App\Models\BlStatus;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlStatusResource extends Resource
{
    protected static ?string $model = BlStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?string $navigationLabel = '비엘상태';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([

                                TextInput::make('code')
                                    ->label('코드(숫자)')
                                    ->required()
                                    ->minLength(1)
                                    ->maxLength(10)
                                    ->autofocus()
                                    ->placeholder('코드번호'),
                                TextInput::make('name')
                                    ->required()
                                    ->label('상태명')
                                    ->maxLength(15)
                                    ->placeholder('상태명'),
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('코드(숫자)'),
                Tables\Columns\TextColumn::make('name')->label('상태명'),
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
            'index' => Pages\ListBlStatuses::route('/'),
            'create' => Pages\CreateBlStatus::route('/create'),
            'edit' => Pages\EditBlStatus::route('/{record}/edit'),
        ];
    }
}
