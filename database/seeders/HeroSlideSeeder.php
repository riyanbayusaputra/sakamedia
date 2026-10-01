<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'badge' => 'OFFICIAL CAMPAIGN',
                'title' => 'Ultra Cepat! Internet Tanpa Batas Kuota',
                'image' => null,
                'link_url' => '#produk-pilihan',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'badge' => 'PROMO GAMER',
                'title' => 'Latensi Rendah untuk Kompetitif Gaming 24 Jam',
                'image' => null,
                'link_url' => '#produk-pilihan',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'badge' => 'PAKET BISNIS',
                'title' => 'Dedicated Bandwidth 1:1 untuk Kantor & Instansi',
                'image' => null,
                'link_url' => '#hubungi-kami',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}