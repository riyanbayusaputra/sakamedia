<?php

namespace App\Filament\Pages;



use App\Filament\Widgets\Laporan\RingkasanAktivitas;
use App\Filament\Widgets\Laporan\RingkasanStatus;
use App\Filament\Widgets\Laporan\RingkasanStok;
use App\Filament\Widgets\Laporan\TabelModemTerpasang;
use App\Filament\Widgets\Laporan\TabelPemasangan;
use App\Filament\Widgets\Laporan\TabelPendaftaran;
use App\Filament\Widgets\Laporan\TabelStokModem;
use App\Models\Product;
use App\Models\User;
use App\Support\ReportFilters;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Laporan extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'laporan';

    protected static ?string $title = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan';

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 20;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Laporan')
                    ->description(
                        'Sesuaikan periode dan kriteria untuk melihat rekap data yang dibutuhkan.'
                    )
                    ->icon('heroicon-o-funnel')
                    ->schema([
                        Select::make('periode')
                            ->label('Periode Laporan')
                            ->options(ReportFilters::presets())
                            ->default('bulan_ini')
                            ->native(false)
                            ->selectablePlaceholder(false)
                            ->live()
                            ->required(),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->placeholder('Pilih tanggal mulai')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->visible(
                                fn ($get): bool =>
                                    $get('periode') === 'custom'
                            )
                            ->required(
                                fn ($get): bool =>
                                    $get('periode') === 'custom'
                            ),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->placeholder('Pilih tanggal selesai')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->visible(
                                fn ($get): bool =>
                                    $get('periode') === 'custom'
                            )
                            ->required(
                                fn ($get): bool =>
                                    $get('periode') === 'custom'
                            ),

                        Select::make('teknisi_id')
                            ->label('Teknisi')
                            ->options(
                                fn (): array => User::query()
                                    ->where('role', 'teknisi')
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all()
                            )
                            ->placeholder('Semua teknisi')
                            ->native(false)
                            ->searchable()
                            ->preload(),

                        Select::make('sales_id')
                            ->label('Sales')
                            ->options(
                                fn (): array => User::query()
                                    ->where('role', 'sales')
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all()
                            )
                            ->placeholder('Semua sales')
                            ->native(false)
                            ->searchable()
                            ->preload(),

                        Select::make('produk_id')
                            ->label('Paket Internet')
                            ->options(
                                fn (): array => Product::query()
                                    ->orderBy('sort_order')
                                    ->pluck('name', 'id')
                                    ->all()
                            )
                            ->placeholder('Semua paket')
                            ->native(false)
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'xl' => 3,
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            RingkasanAktivitas::class,
            RingkasanStatus::class,
            RingkasanStok::class,
            TabelPendaftaran::class,
            TabelPemasangan::class,
            TabelModemTerpasang::class,
            TabelStokModem::class,
        ];
    }
}
