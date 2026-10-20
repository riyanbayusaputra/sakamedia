<?php

namespace App\Livewire;

use Livewire\Component;

class CustomerDashboard extends Component
{
    public string $filter = 'semua';

    public string $search = '';

    // Kelompok status untuk tiap filter
    protected function filterStatuses(): array
    {
        return [
            'baru' => ['baru'],
          'proses' => ['diproses', 'dijadwalkan', 'disurvei', 'pending'],
            'terpasang' => ['terpasang'],
            'dibatalkan' => ['dibatalkan'],
        ];
    }

    public function setFilter(string $filter): void
    {
        // Abaikan nilai di luar daftar yang dikenal
        $this->filter = ($filter === 'semua' || array_key_exists($filter, $this->filterStatuses()))
            ? $filter
            : 'semua';
    }

    public function getCountsProperty(): array
    {
        $rows = auth()->user()->subscriptions()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $count = fn (string ...$statuses) => (int) collect($statuses)->sum(fn ($s) => $rows->get($s, 0));

        return [
            'semua' => (int) $rows->sum(),
            'baru' => $count('baru'),
           'proses' => $count('diproses', 'dijadwalkan', 'disurvei', 'pending'),
            'terpasang' => $count('terpasang'),
            'dibatalkan' => $count('dibatalkan'),
        ];
    }

    public function getSubscriptionsProperty()
    {
        $statuses = $this->filterStatuses()[$this->filter] ?? null;
        $term = trim($this->search);

        return auth()->user()->subscriptions()
            ->with('technician')
            ->when($statuses, fn ($query) => $query->whereIn('status', $statuses))
            ->when($term !== '', function ($query) use ($term) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('installation_address', 'like', $like);
                });
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.customer-dashboard');
    }
}