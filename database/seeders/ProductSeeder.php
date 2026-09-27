<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'slug' => 'saka-lite-50',
                'category' => 'rumah',
                'badge' => 'Rumah Tangga',
                'icon' => 'home',
                'name' => 'Saka Lite 50',
                'description' => 'Ideal untuk browsing cepat & keluarga kecil 3-5 perangkat.',
                'speed' => '50',
                'unit' => 'Mbps',
                'old_price' => 'Rp 250.000',
                'discount' => 'HEMAT 20%',
                'price' => 'Rp 199.000',
                'is_popular' => false,
                'features' => [
                    'Download & Upload 1:1 Simetris',
                    '100% Tanpa Batas FUP / Kuota',
                    'Free Dual Band Wi-Fi Router',
                    'Rekomendasi 3 - 5 Perangkat',
                    'Layanan Bantuan 24/7',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'saka-fast-100',
                'category' => 'rumah',
                'badge' => 'Best Value',
                'icon' => 'local_fire_department',
                'name' => 'Saka Fast 100',
                'description' => 'Favorit streaming 4K & multi-device tanpa buffering.',
                'speed' => '100',
                'unit' => 'Mbps',
                'old_price' => 'Rp 375.000',
                'discount' => 'HEMAT 25%',
                'price' => 'Rp 299.000',
                'is_popular' => true,
                'features' => [
                    '100% Fiber Optic Simetris 1:1',
                    'Tanpa FUP & Kuota Tak Terbatas',
                    'Free Router Wi-Fi 6 Gigabit',
                    'Cocok untuk 6 - 10 Perangkat',
                    'Prioritas Latensi Rendah',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'saka-pro-250',
                'category' => 'gamer',
                'badge' => 'Ultra Fast',
                'icon' => 'sports_esports',
                'name' => 'Saka Pro 250',
                'description' => 'Latensi ultra-rendah untuk kompetitif gaming & streaming.',
                'speed' => '250',
                'unit' => 'Mbps',
                'old_price' => 'Rp 550.000',
                'discount' => 'HEMAT 18%',
                'price' => 'Rp 449.000',
                'is_popular' => false,
                'features' => [
                    'Custom Gaming Route (Ping < 10ms)',
                    'Free Router Wi-Fi 6 AX3000',
                    '1 IP Publik / Prioritas Traffic',
                    'Rekomendasi 10 - 20 Perangkat',
                    'SLA Uptime 99.5% Terjamin',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'slug' => 'dedicated-biz',
                'category' => 'bisnis',
                'badge' => 'Enterprise',
                'icon' => 'domain',
                'name' => 'Dedicated Biz',
                'description' => 'Infrastruktur gigabit murni untuk kantor, hotel & instansi.',
                'speed' => 'Up to 1',
                'unit' => 'Gbps',
                'old_price' => null,
                'discount' => null,
                'price' => 'Rp 1.500.000',
                'is_popular' => false,
                'features' => [
                    'Dedicated Bandwidth Murni 1:1',
                    '1 Alokasi IP Public Statis (/30)',
                    'MRTG & Grafana Monitoring Realtime',
                    'Dual Link Redundant Backbone',
                    '24/7 Dedicated Account Manager',
                ],
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}