<?php

namespace App\Support;

use App\Models\Modem;
use App\Models\ModemModel;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class ReportFilters
{
    public function __construct(
        public readonly CarbonImmutable $start,
        public readonly CarbonImmutable $end,
        public readonly ?int $technicianId = null,
        public readonly ?int $salesId = null,
        public readonly ?int $productId = null,
    ) {
    }

    public static function presets(): array
    {
        return [
            'hari_ini' => 'Hari ini',
            'kemarin' => 'Kemarin',
            '7_hari' => '7 hari terakhir',
            'bulan_ini' => 'Bulan ini',
            'bulan_lalu' => 'Bulan lalu',
            'custom' => 'Rentang tanggal sendiri',
        ];
    }

    // Terima isi form filter (boleh null / sebagian kosong), selalu menghasilkan nilai yang valid
    public static function make(?array $filters): self
    {
        $filters ??= [];
        $now = CarbonImmutable::now();

        $customStart = filled($filters['tanggal_mulai'] ?? null)
            ? CarbonImmutable::parse($filters['tanggal_mulai'])->startOfDay()
            : $now->startOfMonth();

        $customEnd = filled($filters['tanggal_selesai'] ?? null)
            ? CarbonImmutable::parse($filters['tanggal_selesai'])->endOfDay()
            : $now->endOfDay();

        [$start, $end] = match ($filters['periode'] ?? 'bulan_ini') {
            'hari_ini' => [$now->startOfDay(), $now->endOfDay()],
            'kemarin' => [$now->subDay()->startOfDay(), $now->subDay()->endOfDay()],
            '7_hari' => [$now->subDays(6)->startOfDay(), $now->endOfDay()],
            'bulan_lalu' => [
                $now->subMonthNoOverflow()->startOfMonth(),
                $now->subMonthNoOverflow()->endOfMonth(),
            ],
            'custom' => [$customStart, $customEnd],
            default => [$now->startOfMonth(), $now->endOfMonth()],
        };

        // Kalau tanggal terbalik, tukar saja
        if ($start->gt($end)) {
            [$start, $end] = [$end->startOfDay(), $start->endOfDay()];
        }

        return new self(
            $start,
            $end,
            self::toId($filters['teknisi_id'] ?? null),
            self::toId($filters['sales_id'] ?? null),
            self::toId($filters['produk_id'] ?? null),
        );
    }

    private static function toId(mixed $value): ?int
    {
        return filled($value) ? (int) $value : null;
    }

    public function label(): string
    {
        if ($this->start->isSameDay($this->end)) {
            return $this->start->translatedFormat('d M Y');
        }

        return $this->start->translatedFormat('d M Y') . ' – ' . $this->end->translatedFormat('d M Y');
    }

    // Ringkasan filter untuk ditulis di bagian atas file export
    public function describeFilters(): string
    {
        return implode(' · ', [
            $this->technicianId ? 'Teknisi: ' . (User::find($this->technicianId)?->name ?? '-') : 'Semua teknisi',
            $this->salesId ? 'Sales: ' . (User::find($this->salesId)?->name ?? '-') : 'Semua sales',
            $this->productId ? 'Paket: ' . (Product::find($this->productId)?->name ?? '-') : 'Semua paket',
        ]);
    }

    public function hasSubscriptionFilters(): bool
    {
        return $this->technicianId || $this->salesId || $this->productId;
    }

    // Pendaftaran yang dibuat dalam periode
    public function registrations(): Builder
    {
        return $this->applySubscriptionFilters(
            Subscription::query()->whereBetween('created_at', [$this->start, $this->end])
        );
    }

    // Laporan survei yang dikirim teknisi dalam periode
    public function surveys(): Builder
    {
        return $this->applySubscriptionFilters(
            Subscription::query()->whereBetween('surveyed_at', [$this->start, $this->end])
        );
    }

    // Pelanggan yang diaktivasi (status Terpasang) dalam periode
    public function activations(): Builder
    {
        return $this->applySubscriptionFilters(
            Subscription::query()
                ->where('status', 'terpasang')
                ->whereBetween('activated_at', [$this->start, $this->end])
        );
    }

    // Unit modem yang dipasang dalam periode
    public function installedModems(): Builder
    {
        return Modem::query()
            ->where('status', 'terpasang')
            ->whereBetween('installed_at', [$this->start, $this->end])
            ->when(
                $this->hasSubscriptionFilters(),
                fn (Builder $query) => $query->whereHas(
                    'subscription',
                    fn (Builder $subscription) => $this->applySubscriptionFilters($subscription)
                )
            );
    }

    // Rekap stok per model modem (jumlah dihitung di database)
    public function stockByModel(): Builder
    {
        return ModemModel::query()->withCount([
            'modems as total_count',
            'modems as tersedia_count' => fn ($query) => $query->where('status', 'tersedia'),
            'modems as rusak_count' => fn ($query) => $query->where('status', 'rusak'),
            'modems as masuk_count' => fn ($query) => $query
                ->whereBetween('created_at', [$this->start, $this->end]),
            'modems as dipasang_count' => fn ($query) => $query
                ->where('status', 'terpasang')
                ->whereBetween('installed_at', [$this->start, $this->end]),
        ]);
    }

    // Terapkan filter teknisi, sales, dan paket ke query pendaftaran
    private function applySubscriptionFilters(Builder $query): Builder
    {
        return $query
            ->when($this->technicianId, fn (Builder $q) => $q->where('technician_id', $this->technicianId))
            ->when($this->salesId, fn (Builder $q) => $q->where('user_id', $this->salesId))
            ->when($this->productId, fn (Builder $q) => $q->where('product_id', $this->productId));
    }
}