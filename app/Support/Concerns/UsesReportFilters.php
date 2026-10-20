<?php

namespace App\Support\Concerns;

use App\Support\ReportExporter;
use App\Support\ReportFilters;
use Filament\Actions\Action;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

trait UsesReportFilters
{
    use InteractsWithPageFilters;

    protected function report(): ReportFilters
    {
        return ReportFilters::make($this->pageFilters);
    }

    // Tombol export Excel dan CSV, memakai filter yang sedang aktif di halaman
    protected function exportActions(string $report): array
    {
        return [
            Action::make('export_xlsx')
                ->label('Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(fn () => ReportExporter::download($report, 'xlsx', $this->report())),

            Action::make('export_csv')
                ->label('CSV')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(fn () => ReportExporter::download($report, 'csv', $this->report())),
        ];
    }
}