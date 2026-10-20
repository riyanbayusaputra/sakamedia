<?php

namespace App\Filament\Resources\Subscriptions;

use App\Filament\Resources\Subscriptions\Pages;
use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\DateTimePicker;

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

    public static function statusOptions(): array
    {
        return [
            'baru' => 'Baru',
            'pending' => 'Pending',
            'dijadwalkan' => 'Dijadwalkan',
            'disurvei' => 'Disurvei',
            'terpasang' => 'Terpasang',
            'dibatalkan' => 'Dibatalkan',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pelanggan')
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Paket Dipilih')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama')
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
                            ->label('Foto modem / KTP')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('subscriptions')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Penugasan Survei')
                    ->schema([
                        Select::make('technician_id')
                            ->label('Teknisi Ditugaskan')
                            ->relationship('technician', 'name', fn ($query) => $query->where('role', 'teknisi'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Begitu teknisi dipilih, status otomatis jadi "Dijadwalkan"
                                // (kalau mau menahan sebagai Pending, pilih Pending sesudahnya)
                                if ($state && $get('status') === 'baru') {
                                    $set('status', 'dijadwalkan');
                                }
                            })
                            ->helperText('Hanya akun dengan peran Teknisi yang muncul di sini.'),
                        DatePicker::make('survey_date')
                            ->label('Tanggal Survei')
                            ->native(false)
                            ->displayFormat('d M Y'),
                        Select::make('status')
                        ->label('Status')
                        ->options(static::statusOptions())
                        ->default('baru')
                        ->required()
                        ->live()
                        ->helperText('Pending = menunggu konfirmasi pelanggan. Teknisi hanya bisa melihat, belum bisa mengisi formulir.'),
                        DateTimePicker::make('activated_at')
                        ->label('Tanggal Aktivasi')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d M Y, H:i')
                        ->visible(fn ($get) => $get('status') === 'terpasang')
                        ->helperText('Terisi otomatis saat status Terpasang. Boleh dikoreksi manual.'),
                    ])->columns(2),


                Section::make('Laporan Teknisi')
                    ->description('Diisi teknisi dari halaman lapangan. Admin juga bisa mengoreksi di sini.')
                    ->schema([
                        TextInput::make('team')
                            ->label('Team'),
                        TextInput::make('odp')
                            ->label('ODP'),
                        TextInput::make('odp_port')
                            ->label('ODP Port'),
                        TextInput::make('odc')
                            ->label('ODC'),
                        Repeater::make('materials')
                            ->label('Material')
                            ->simple(
                                TextInput::make('item')
                                    ->label('Material')
                                    ->required()
                            )
                            ->addActionLabel('+ Tambah Material')
                            ->columnSpanFull(),
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),
                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),
                        FileUpload::make('ktp_photo')
                            ->label('Foto KTP / Modem ')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('ktp')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),
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
                TextColumn::make('name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('user.name')
                    ->label('Diinput Oleh')
                    ->badge()
                    ->color('info')
                    ->default('Publik / Tanpa Login'),
                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->badge()
                    ->color('warning')
                    ->default('Belum ditugaskan'),
                TextColumn::make('survey_date')
                    ->label('Tgl Survei')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => static::statusOptions()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'pending' => 'info',
                        'dijadwalkan', 'disurvei' => 'warning',
                        'terpasang' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('odp')
                    ->label('ODP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('surveyed_at')
                    ->label('Dilaporkan')
                    ->dateTime('d M Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('activated_at')
                    ->label('Aktivasi')
                    ->dateTime('d M Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Daftar Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(static::statusOptions()),
                SelectFilter::make('technician_id')
                    ->label('Teknisi')
                    ->relationship('technician', 'name', fn ($query) => $query->where('role', 'teknisi')),
            ])
            ->recordActions([
                Action::make('maps')
                    ->label('Maps')
                    ->icon('heroicon-o-map-pin')
                    ->url(fn (Subscription $record) => $record->maps_url)
                    ->openUrlInNewTab()
                    ->visible(fn (Subscription $record) => filled($record->maps_url)),

                Action::make('pending')
                    ->label('Pending')
                    ->icon('heroicon-o-pause-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai pending?')
                    ->modalDescription('Pendaftaran ini ditandai menunggu konfirmasi pelanggan. Teknisi hanya bisa melihatnya sampai Anda menjadwalkan kembali.')
                    ->visible(fn (Subscription $record) => in_array($record->status, ['baru', 'dijadwalkan'], true))
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'pending']);

                        Notification::make()
                            ->title('Ditandai pending')
                            ->success()
                            ->send();
                    }),

                Action::make('konfirmasi')
                    ->label('Jadwalkan')
                    ->icon('heroicon-o-play-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Pelanggan sudah konfirmasi?')
                    ->modalDescription('Status berubah menjadi Dijadwalkan, lalu teknisi bisa mulai mengisi formulir pemasangan.')
                    ->visible(fn (Subscription $record) => $record->status === 'pending')
                    ->action(function (Subscription $record) {
                        if (! $record->technician_id) {
                            Notification::make()
                                ->title('Pilih teknisi dulu')
                                ->body('Tentukan teknisi dan tanggal survei lewat tombol Edit, baru jadwalkan.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $record->update(['status' => 'dijadwalkan']);

                        Notification::make()
                            ->title('Dijadwalkan. Teknisi sudah bisa mengisi formulir.')
                            ->success()
                            ->send();
                    }),

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