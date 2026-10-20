<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/teknisi/tugas/{subscription}', function (\App\Models\Subscription $subscription) {
    abort_unless(
        auth()->user()->isTechnician() && $subscription->technician_id === auth()->id(),
        403,
        'Tugas ini bukan untuk Anda.'
    );

    return view('technician-report', ['subscription' => $subscription]);
})->name('technician.report');

Route::get('/berita/{slug}', function (string $slug) {
    return view('article-detail', ['slug' => $slug]);
})->name('articles.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('login'))->name('login');
    Route::get('/register', fn () => view('register'))->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Teknisi punya halaman sendiri
        if (auth()->user()->isTechnician()) {
            return redirect()->route('technician.tasks');
        }

        return view('dashboard');
    })->name('dashboard');

    Route::get('/teknisi', function () {
        abort_unless(auth()->user()->isTechnician(), 403, 'Halaman ini khusus teknisi.');

        return view('technician');
    })->name('technician.tasks');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');