<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wifi';

    protected static ?string $navigationLabel = 'Paket Internet';

    protected static ?string $modelLabel = 'Paket';

    protected static ?string $pluralModelLabel = 'Paket Internet';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        TextInput::make('slug')
                            ->label('Slug (identifier unik)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis terisi dari nama, tapi bisa diedit manual.'),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->rows(2),
                    ])->columns(2),

                Section::make('Kategori & Tampilan')
                    ->schema([
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'rumah' => 'Internet Rumah & Wi-Fi',
                                'gamer' => 'Paket Gamer & Streaming',
                                'bisnis' => 'Dedicated Instansi & Bisnis',
                            ])
                            ->required(),
                        TextInput::make('badge')
                            ->label('Label Badge')
                            ->required()
                            ->helperText('Contoh: Rumah Tangga, Best Value, Enterprise'),
                        TextInput::make('icon')
                            ->label('Nama Icon (Material Symbols)')
                            ->required()
                            ->helperText('Contoh: home, sports_esports, domain. Cek di fonts.google.com/icons'),
                        Toggle::make('is_popular')
                            ->label('Tandai sebagai "Paling Populer"'),
                    ])->columns(2),

                Section::make('Kecepatan & Harga')
                    ->schema([
                        TextInput::make('speed')
                            ->label('Kecepatan')
                            ->required()
                            ->helperText('Contoh: 50, 100, "Up to 1"'),
                        Select::make('unit')
                            ->label('Satuan')
                            ->options([
                                'Mbps' => 'Mbps',
                                'Gbps' => 'Gbps',
                            ])
                            ->required(),
                        TextInput::make('price')
                            ->label('Harga Sekarang')
                            ->required()
                            ->helperText('Contoh: Rp 199.000'),
                        TextInput::make('old_price')
                            ->label('Harga Coret (opsional)')
                            ->helperText('Kosongkan jika tidak ada diskon'),
                        TextInput::make('discount')
                            ->label('Label Diskon (opsional)')
                            ->helperText('Contoh: HEMAT 20%'),
                    ])->columns(2),

                Section::make('Daftar Fitur')
                    ->schema([
                        Repeater::make('features')
                            ->label('Fitur Paket')
                            ->simple(
                                TextInput::make('feature')
                                    ->label('Fitur')
                                    ->required()
                            )
                            ->addActionLabel('+ Tambah Fitur')
                            ->reorderable()
                            ->required(),
                    ]),

                Section::make('Pengaturan Tampil')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil tampil lebih dulu'),
                        Toggle::make('is_active')
                            ->label('Aktif (tampilkan di website)')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('speed')
                    ->label('Kecepatan')
                    ->formatStateUsing(fn ($record) => $record->speed . ' ' . $record->unit),
                TextColumn::make('price')
                    ->label('Harga'),
                IconColumn::make('is_popular')
                    ->label('Populer')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'rumah' => 'Internet Rumah & Wi-Fi',
                        'gamer' => 'Paket Gamer & Streaming',
                        'bisnis' => 'Dedicated Instansi & Bisnis',
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}