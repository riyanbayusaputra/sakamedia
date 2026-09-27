<?php

namespace App\Livewire;

use Livewire\Component;

class CoverageChecker extends Component
{
    // Wilayah yang dipilih user dari dropdown
    public string $region = 'pemalang';

    // Alamat yang diketik user
    public string $address = '';

    // Menyimpan apakah hasil pengecekan sudah tampil atau belum
    public bool $checked = false;

    public function checkCoverage()
    {
        // Validasi sederhana: alamat wajib diisi
        $this->validate([
            'address' => 'required|min:5',
        ], [
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat terlalu pendek, mohon tulis lebih detail.',
        ]);

        // Untuk sekarang, anggap semua lokasi selalu masuk cakupan.
        // Nanti logic ini bisa diganti cek ke database area cakupan asli.
        $this->checked = true;
    }

    public function render()
    {
        return view('livewire.coverage-checker');
    }
}