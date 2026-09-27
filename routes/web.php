<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/berita/{slug}', function (string $slug) {
    return view('article-detail', ['slug' => $slug]);
})->name('articles.show');
