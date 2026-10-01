<?php

namespace App\Filament\Resources\Subscriptions;

use App\Filament\Resources\Subscriptions\Pages;
use App\Models\Subscription;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Pendaftaran Pemasangan';

    protected static ?string $modelLabel = 'Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftaran Pemasangan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'baru')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pendaftar')
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Paket Dipilih')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->required(),
                        Textarea::make('installation_address')
                            ->label('Alamat Pemasangan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('photo')
                            ->label('Foto Lokasi Pemasangan')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('subscriptions')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status Tindak Lanjut')
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'disurvei' => 'Sedang Disurvei',
                                'terpasang' => 'Terpasang',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')
                    ->height(50),
                TextColumn::make('product_name')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('user.name')
                ->label('Diinput Oleh')
                ->badge()
                 ->color('info')
                ->default('Publik / Tanpa Login'),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('installation_address')
                    ->label('Alamat')
                    ->limit(30),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'baru',
                        'warning' => fn ($state) => in_array($state, ['diproses', 'disurvei']),
                        'success' => 'terpasang',
                        'secondary' => 'dibatalkan',
                    ]),
                TextColumn::make('created_at')
                    ->label('Daftar Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'disurvei' => 'Sedang Disurvei',
                        'terpasang' => 'Terpasang',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}