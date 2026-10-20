<?php

namespace App\Livewire;

use Livewire\Component;

class TechnicianTasks extends Component
{
    public function mount()
    {
        abort_unless(auth()->user()->isTechnician(), 403);
    }

    public function getTasksProperty(): array
    {
        $today = today();
        $user = auth()->user();

        $pending = $user->assignedSubscriptions()
            ->with('user')
            ->where('status', 'dijadwalkan')
            ->get();

        return [
            'today' => $pending
                ->filter(fn ($task) => $task->survey_date?->isSameDay($today))
                ->values(),

            'overdue' => $pending
                ->filter(fn ($task) => $task->survey_date && $task->survey_date->lt($today))
                ->sortBy('survey_date')
                ->values(),

            // Tugas tanpa tanggal ikut di "Mendatang", diurutkan paling akhir
            'upcoming' => $pending
                ->filter(fn ($task) => ! $task->survey_date || $task->survey_date->gt($today))
                ->sortBy(fn ($task) => $task->survey_date?->timestamp ?? PHP_INT_MAX)
                ->values(),

            // Menunggu konfirmasi pelanggan (hanya dilihat, belum bisa diisi)
            'awaiting' => $user->assignedSubscriptions()
                ->with('user')
                ->where('status', 'pending')
                ->latest()
                ->get(),

            // Laporan yang sudah dikirim (10 terbaru)
            'reported' => $user->assignedSubscriptions()
                ->where('status', 'disurvei')
                ->latest('surveyed_at')
                ->limit(10)
                ->get(),

            // Jumlah laporan yang dikirim hari ini (untuk bar progres)
            'reported_today' => $user->assignedSubscriptions()
                ->whereIn('status', ['disurvei', 'terpasang'])
                ->whereDate('surveyed_at', $today)
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.technician-tasks');
    }
}