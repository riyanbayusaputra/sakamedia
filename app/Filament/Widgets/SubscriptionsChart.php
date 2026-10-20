<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionsChart extends ChartWidget
{
    protected ?string $heading = 'Pendaftaran Pemasangan (6 Bulan Terakhir)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $rows = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);

            return [
                'label' => $date->translatedFormat('M Y'),
                'count' => Subscription::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftaran',
                    'data' => $rows->pluck('count')->toArray(),
                    'backgroundColor' => '#1D4ED8',
                    'borderColor' => '#1D4ED8',
                ],
            ],
            'labels' => $rows->pluck('label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}