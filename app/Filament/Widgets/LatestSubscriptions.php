<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSubscriptions extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pendaftaran Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(Subscription::query()->latest())
            ->paginated([5])
            ->columns([
                TextColumn::make('name')
                    ->label('Pelanggan'),
                TextColumn::make('product_name')
                    ->label('Paket'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('user.name')
                    ->label('Diinput Oleh')
                    ->default('—'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'baru',
                        'warning' => fn ($state) => in_array($state, ['diproses', 'disurvei']),
                        'success' => 'terpasang',
                        'secondary' => 'dibatalkan',
                    ]),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i'),
            ]);
    }
}