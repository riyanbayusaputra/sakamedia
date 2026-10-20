<?php

namespace App\Filament\Widgets\Laporan;

use App\Support\Concerns\UsesReportFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TabelModemTerpasang extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Rekap Modem Terpasang (SN)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->report()->installedModems()->with([
                'modemModel',
                'subscription.technician',
                'subscription.user',
            ]))
            ->headerActions($this->exportActions('modem'))
            ->columns([
                TextColumn::make('installed_at')
                    ->label('Tgl Pasang')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('serial_number')
                    ->label('SN')
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('modemModel.name')
                    ->label('Model'),
                TextColumn::make('modemModel.type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => strtoupper((string) $state)),
                TextColumn::make('subscription.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('subscription.technician.name')
                    ->label('Teknisi')
                    ->default('—'),
                TextColumn::make('subscription.team')
                    ->label('Team')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('subscription.user.name')
                    ->label('Sales')
                    ->default('—')
                    ->toggleable(),
            ])
            ->defaultSort('installed_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Belum ada modem terpasang pada periode ini');
    }
}