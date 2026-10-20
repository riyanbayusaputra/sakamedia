<?php

namespace App\Filament\Widgets\Laporan;

use App\Support\Concerns\UsesReportFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanStatus extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $counts = $this->report()
            ->registrations()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $total = (int) $counts->sum();

        $items = [
            'baru' => ['Masih Baru', 'danger'],
            'pending' => ['Pending', 'info'],
            'dijadwalkan' => ['Dijadwalkan', 'warning'],
            'disurvei' => ['Disurvei', 'warning'],
            'terpasang' => ['Sudah Terpasang', 'success'],
            'dibatalkan' => ['Dibatalkan', 'gray'],
        ];

        $stats = [];

        foreach ($items as $status => [$label, $color]) {
            $count = (int) $counts->get($status, 0);
            $percent = $total > 0 ? round($count / $total * 100) : 0;

            $stats[] = Stat::make($label, number_format($count))
                ->description($percent . '% dari ' . number_format($total) . ' pendaftaran periode ini')
                ->color($color);
        }

        return $stats;
    }
}