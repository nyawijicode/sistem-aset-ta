<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Kelola User';

    protected static ?string $modelLabel = 'User';

    protected static ?int $navigationSort = 4;

    // Menu ini HANYA muncul & bisa diakses oleh Pimpinan
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isPimpinan() ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isPimpinan() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isPimpinan() ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->isPimpinan() ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isPimpinan() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Forms\Components\Select::make('role')
                ->label('Role')
                ->options([
                    'pimpinan' => 'Pimpinan',
                    'admin' => 'Admin',
                ])
                ->required(),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(fn(string $context) => $context === 'create')
                ->dehydrated(fn($state) => filled($state))
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('role')->label('Role')->badge()
                    ->color(fn(string $state) => $state === 'pimpinan' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        // Tidak ada header action "Cetak Laporan" di sini (sesuai requirement no. 5)
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
