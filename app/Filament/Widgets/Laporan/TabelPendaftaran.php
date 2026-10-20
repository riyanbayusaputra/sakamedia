<?php

namespace App\Filament\Widgets\Laporan;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Support\Concerns\UsesReportFilters;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TabelPendaftaran extends BaseWidget
{
    use UsesReportFilters;

    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Rekap Pendaftaran';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->report()->registrations()->with(['user', 'technician']))
            ->headerActions($this->exportActions('pendaftaran'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('installation_address')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->installation_address),
                TextColumn::make('product_name')
                    ->label('Paket'),
                TextColumn::make('user.name')
                    ->label('Sales')
                    ->default('—'),
                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->default('—'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => SubscriptionResource::statusOptions()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'pending' => 'info',
                        'dijadwalkan', 'disurvei' => 'warning',
                        'terpasang' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Tidak ada pendaftaran pada periode ini');
    }
}