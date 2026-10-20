<?php

namespace App\Filament\Widgets\Laporan;

use App\Models\Modem;
use App\Support\Concerns\UsesReportFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanStok extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $report = $this->report();

        return [
            Stat::make('Stok Tersedia', number_format(Modem::where('status', 'tersedia')->count()))
                ->description('Siap dipasang (kondisi saat ini)')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('success'),

            Stat::make('Terpasang (Total)', number_format(Modem::where('status', 'terpasang')->count()))
                ->description('Seluruh unit yang sedang terpasang')
                ->descriptionIcon('heroicon-m-server')
                ->color('info'),

            Stat::make('Rusak / Retur', number_format(Modem::where('status', 'rusak')->count()))
                ->description('Tidak bisa dipasang')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Stok Masuk', number_format(Modem::whereBetween('created_at', [$report->start, $report->end])->count()))
                ->description('Ditambahkan · ' . $report->label())
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('primary'),
        ];
    }
}