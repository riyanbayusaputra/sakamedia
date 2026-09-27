<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'facebook-lawan-berita-fake',
                'title' => 'Facebook Meluas Melawan Berita Fake Dengan Artikel Otomatis',
                'category' => 'Teknologi',
                'author' => 'Aris Putra Buana',
                'excerpt' => 'Setelah pemilihan Presiden, ketika Facebook menghadapi pengawasan ketat atas penyebaran konten, sistem algoritma artikel otomatis diperkenalkan.',
                'content' => null,
                'cover_image' => null,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'slug' => 'steve-jobs-perusahaan-atari',
                'title' => 'Steve Jobs dengan Perusahaan Legendaris ATARI',
                'category' => 'Biografi',
                'author' => 'Muhammad Iqbal',
                'excerpt' => 'Kisah di Atari pada Februari 1974, setelah Steve Jobs menghabiskan waktu di universitas dan mendalami dasar pembuatan sistem komputer mikro.',
                'content' => null,
                'cover_image' => null,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'slug' => 'tiga-cpu-8-core-549',
                'title' => 'Tiga CPU Termasuk 8-Core dengan Harga Mulai $549',
                'category' => 'Teknologi',
                'author' => 'Aris Putra Buana',
                'excerpt' => 'Penetapan harga akhir dan jajaran prosesor desktop high-end baru untuk komputasi berat, streaming, dan pengolahan server fiber berkecepatan tinggi.',
                'content' => null,
                'cover_image' => null,
                'is_published' => true,
                'published_at' => now()->subDays(8),
            ],
            [
                'slug' => 'steve-jobs-apple-i',
                'title' => 'Steve Jobs dan Sejarah Terciptanya Komputer APPLE I',
                'category' => 'Biografi',
                'author' => 'Aris Putra Buana',
                'excerpt' => 'Selama akhir 1960-an dan era 70-an, penyatuan arus budaya San Francisco dan Silicon Valley melahirkan inovasi komputer personal pertama di dunia.',
                'content' => null,
                'cover_image' => null,
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}