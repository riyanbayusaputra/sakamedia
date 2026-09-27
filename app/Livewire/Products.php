<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Products extends Component
{
    // Kategori yang sedang aktif dipilih user
    public string $activeCategory = 'semua';

    // Daftar kategori untuk tombol filter
    public array $categories = [
        'semua' => 'Semua Paket',
        'rumah' => 'Internet Rumah & Wi-Fi',
        'gamer' => 'Paket Gamer & Streaming',
        'bisnis' => 'Dedicated Instansi & Bisnis',
    ];

    // Method dipanggil saat user klik salah satu tombol kategori
    public function setCategory(string $category)
    {
        $this->activeCategory = $category;
    }

    // Mengambil daftar produk dari database, sudah otomatis ter-filter & terurut
    public function getProductsProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->when(
                $this->activeCategory !== 'semua',
                fn ($query) => $query->where('category', $this->activeCategory)
            )
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('livewire.products');
    }
}