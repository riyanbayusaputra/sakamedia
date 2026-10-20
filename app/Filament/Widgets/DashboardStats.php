<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\ContactSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Paket Aktif', Product::where('is_active', true)->count())
                ->description('Tampil di website saat ini')
                ->descriptionIcon('heroicon-m-wifi')
                ->color('primary'),

            Stat::make('Pendaftaran Baru', Subscription::where('status', 'baru')->count())
                ->description('Belum ditindaklanjuti')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('Total Pendaftaran', Subscription::count())
                ->description('Sepanjang waktu')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success'),

            Stat::make('Pesan Masuk Baru', ContactSubmission::where('status', 'baru')->count())
                ->description('Dari form Hubungi Kami')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
        ];
    }
}