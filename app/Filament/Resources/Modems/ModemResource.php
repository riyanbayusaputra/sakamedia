<?php

namespace App\Filament\Resources\Modems;

use App\Filament\Resources\Modems\Pages;
use App\Models\Modem;
use App\Models\ModemModel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ModemResource extends Resource
{
    protected static ?string $model = Modem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-server';

    protected static ?string $navigationLabel = 'Stok Modem';

    protected static ?string $modelLabel = 'Unit Modem';

    protected static ?string $pluralModelLabel = 'Stok Modem';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Inventaris Modem';
    }

    // Badge di menu = jumlah unit yang masih tersedia
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'tersedia')->count() ?: null;
    }

    public static function statusOptions(): array
    {
        return [
            'tersedia' => 'Tersedia',
            'terpasang' => 'Terpasang',
            'rusak' => 'Rusak / Retur',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Unit')
                    ->schema([
                        Select::make('modem_model_id')
                            ->label('Model Modem')
                            ->relationship('modemModel', 'name')
                            ->getOptionLabelFromRecordUsing(fn (ModemModel $record) => $record->label)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Model belum ada? Tambahkan dulu di menu Model Modem.'),

                        // Saat membuat: tempel banyak SN sekaligus
                        Textarea::make('serial_numbers_bulk')
                            ->label('Serial Number (SN)')
                            ->helperText('Satu SN per baris. Boleh tempel banyak sekaligus dari Excel. SN yang sudah terdaftar akan dilewati.')
                            ->rows(8)
                            ->required()
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->columnSpanFull(),

                        // Saat mengedit: satu SN
                        TextInput::make('serial_number')
                            ->label('Serial Number (SN)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn ($state) => Str::upper(preg_replace('/\s+/', '', (string) $state)))
                            ->visible(fn (string $operation): bool => $operation === 'edit'),

                        Select::make('status')
                            ->label('Status')
                            ->options(static::statusOptions())
                            ->default('tersedia')
                            ->required()
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial_number')
                    ->label('SN')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                TextColumn::make('modemModel.name')
                    ->label('Model')
                    ->searchable(),
                TextColumn::make('modemModel.type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => strtoupper((string) $state)),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => static::statusOptions()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'terpasang' => 'info',
                        'rusak' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('subscription.name')
                    ->label('Dipakai Pelanggan')
                    ->default('—'),
                TextColumn::make('installed_at')
                    ->label('Dipasang')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Masuk Stok')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(static::statusOptions()),
                SelectFilter::make('modem_model_id')
                    ->label('Model')
                    ->relationship('modemModel', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                // Unit yang sedang terpasang tidak bisa dihapus
                DeleteAction::make()
                    ->hidden(fn (Modem $record) => $record->status === 'terpasang'),
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
            'index' => Pages\ListModems::route('/'),
            'create' => Pages\CreateModem::route('/create'),
            'edit' => Pages\EditModem::route('/{record}/edit'),
        ];
    }
}