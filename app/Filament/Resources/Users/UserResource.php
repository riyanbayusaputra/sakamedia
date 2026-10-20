<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Manajemen User';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Manajemen User';

    protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Akun')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Kosongkan kalau tidak ingin mengubah password saat edit.'),
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'sales' => 'Sales',
                                'teknisi' => 'Teknisi',
                            ])
                            ->default('sales')
                            ->required(),
                        Toggle::make('is_admin')
                            ->label('Akses Admin Panel')
                            ->helperText('Aktifkan supaya akun ini bisa login ke /admin. Biarkan nonaktif untuk sales/teknisi.')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->color(fn (string $state): string => $state === 'teknisi' ? 'warning' : 'info'),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),
                TextColumn::make('subscriptions_count')
                    ->label('Pelanggan Didaftarkan')
                    ->counts('subscriptions')
                    ->badge()
                    ->color('info'),
                TextColumn::make('assigned_subscriptions_count')
                    ->label('Tugas Survei')
                    ->counts('assignedSubscriptions')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('created_at')
                    ->label('Terdaftar Pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran')
                    ->options([
                        'sales' => 'Sales',
                        'teknisi' => 'Teknisi',
                    ]),
                TernaryFilter::make('is_admin')
                    ->label('Status Admin')
                    ->placeholder('Semua')
                    ->trueLabel('Admin saja')
                    ->falseLabel('Non-admin saja'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (User $record) {
                        abort_if($record->id === auth()->id(), 403, 'Anda tidak bisa menghapus akun Anda sendiri.');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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