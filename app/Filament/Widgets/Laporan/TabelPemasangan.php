<?php

namespace App\Filament\Widgets\Laporan;

use App\Support\Concerns\UsesReportFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TabelPemasangan extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Rekap Pemasangan (Aktivasi)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->report()->activations()->with(['user', 'technician', 'modem.modemModel']))          
              ->headerActions($this->exportActions('pemasangan'))

            ->columns([
                TextColumn::make('activated_at')
                    ->label('Tgl Aktivasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('installation_address')
                    ->label('Alamat')
                    ->limit(35)
                    ->tooltip(fn ($record): string => $record->installation_address),
                TextColumn::make('product_name')
                    ->label('Paket'),
                TextColumn::make('user.name')
                    ->label('Sales')
                    ->default('—'),
                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->default('—'),
                TextColumn::make('team')
                    ->label('Team')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('odp')
                    ->label('ODP')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('odp_port')
                    ->label('ODP Port')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('odc')
                    ->label('ODC')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('modem.modemModel.name')
                    ->label('Modem')
                    ->default('—')
                    ->toggleable(),
                TextColumn::make('modem.serial_number')
                    ->label('SN Modem')
                    ->fontFamily('mono')
                    ->default('—')
                    ->toggleable(),
            ])
            ->defaultSort('activated_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Belum ada pemasangan pada periode ini');
    }
}