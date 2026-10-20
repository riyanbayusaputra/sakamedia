<?php

namespace App\Filament\Widgets\Laporan;

use App\Support\Concerns\UsesReportFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanAktivitas extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $report = $this->report();
        $label = $report->label();

        return [
            Stat::make('Pendaftaran Masuk', number_format($report->registrations()->count()))
                ->description('Tanggal daftar · ' . $label)
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Laporan Survei', number_format($report->surveys()->count()))
                ->description('Dikirim teknisi · ' . $label)
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),

            Stat::make('Terpasang (Aktivasi)', number_format($report->activations()->count()))
                ->description('Diaktivasi · ' . $label)
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Modem Terpasang', number_format($report->installedModems()->count()))
                ->description('Unit dipasang · ' . $label)
                ->descriptionIcon('heroicon-m-server')
                ->color('info'),
        ];
    }
}