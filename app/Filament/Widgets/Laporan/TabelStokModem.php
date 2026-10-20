<?php

namespace App\Filament\Widgets\Laporan;

use App\Support\Concerns\UsesReportFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TabelStokModem extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Rekap Stok Modem per Model';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->report()->stockByModel())
            ->headerActions($this->exportActions('stok'))
            ->columns([
                TextColumn::make('name')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => strtoupper($state)),
                TextColumn::make('masuk_count')
                    ->label('Masuk (periode)')
                    ->alignEnd(),
                TextColumn::make('dipasang_count')
                    ->label('Dipasang (periode)')
                    ->alignEnd(),
                TextColumn::make('tersedia_count')
                    ->label('Tersedia sekarang')
                    ->badge()
                    ->color('success')
                    ->alignEnd(),
                TextColumn::make('rusak_count')
                    ->label('Rusak / Retur')
                    ->alignEnd(),
                TextColumn::make('total_count')
                    ->label('Total unit')
                    ->alignEnd(),
            ])
            ->defaultSort('name')
            ->paginated(false)
            ->emptyStateHeading('Belum ada model modem');
    }
}