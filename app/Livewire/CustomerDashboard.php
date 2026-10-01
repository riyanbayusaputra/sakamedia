<?php

namespace App\Livewire;

use Livewire\Component;

class CustomerDashboard extends Component
{
    public function getSubscriptionsProperty()
    {
        return auth()->user()
            ->subscriptions()
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.customer-dashboard');
    }
}