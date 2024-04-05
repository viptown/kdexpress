<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = '시스템 설정';
    protected static ?string $navigationLabel = '사용자';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('상용자명')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(50)
                                    ->autofocus()
                                    ->placeholder('사용자명을 입력해주세요.'),
                                TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->maxLength(20)
                                    ->label('이메일')
                                    ->placeholder('이메일 입력해주세요.'),
                                //TextInput::make('email_varified_at'),
                                TextInput::make('password')
                                    ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                                    ->password()
                                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->maxLength(255)
                                    ->label('비밀번호')
                                    ->placeholder('비밀번호 입력해주세요.'),
                                Select::make('role_id')
                                    ->relationship(name: 'role', titleAttribute: 'name'),
                                Select::make('supplier_id')
                                    ->relationship(name: 'supplier', titleAttribute: 'supplier_name'),
                            ])->columns(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('사용자명'),
                Tables\Columns\TextColumn::make('email')->label('이메일'),
                Tables\Columns\TextColumn::make('role.name')->label('역할'),
                Tables\Columns\TextColumn::make('supplier.supplier_name')->label('업체명'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
