<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class NewsSection extends Component
{
    public function getArticlesProperty()
    {
        return Article::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.news-section');
    }
}