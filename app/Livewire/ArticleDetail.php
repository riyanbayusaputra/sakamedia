<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleDetail extends Component
{
    public Article $article;

    // Dipanggil otomatis saat komponen pertama kali dimuat, menerima slug dari route
    public function mount(string $slug)
    {
        $this->article = Article::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail(); // otomatis munculkan halaman 404 kalau tidak ketemu
    }

    // Mengambil 3 artikel lain untuk rekomendasi "Baca Juga"
    public function getRelatedArticlesProperty()
    {
        return Article::query()
            ->where('is_published', true)
            ->where('id', '!=', $this->article->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.article-detail');
    }
}