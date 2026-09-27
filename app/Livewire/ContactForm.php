<?php

namespace App\Livewire;

use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $category = 'instansi';
    public string $message = '';

    public bool $submitted = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:9',
            'category' => 'required',
            'message' => 'nullable|string',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama terlalu pendek.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon terlalu pendek.',
        ];
    }

    public function submit()
    {
        $this->validate();

        // TODO: nanti di sini bisa ditambahkan:
        // - simpan ke database (model ContactSubmission)
        // - kirim email notifikasi ke tim sales
        // Untuk sekarang, kita anggap berhasil dan reset form.

        $this->reset(['name', 'email', 'phone', 'message']);
        $this->category = 'instansi';
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}