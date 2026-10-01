<?php

namespace App\Livewire;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Hero extends Component
{
    public function getSlidesProperty()
    {
        $slides = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($slides->isEmpty()) {
            // Fallback kalau admin belum menambahkan banner sama sekali
            return [
                [
                    'badge' => 'OFFICIAL CAMPAIGN',
                    'title' => 'Ultra Cepat! Internet Tanpa Batas Kuota',
                    'image' => null,
                    'link' => '#produk-pilihan',
                ],
            ];
        }

        return $slides->map(fn ($slide) => [
            'badge' => $slide->badge,
            'title' => $slide->title,
            'image' => $slide->image ? Storage::url($slide->image) : null,
            'link' => $slide->link_url ?: '#produk-pilihan',
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.hero');
    }
}