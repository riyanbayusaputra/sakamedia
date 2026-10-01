<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use App\Services\TelegramNotifier;
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
        $validated = $this->validate();

        $submission = ContactSubmission::create($validated);

        $categoryLabels = [
            'instansi' => 'Dedicated Instansi',
            'rtrw' => 'Dedicated Pengusaha / RT RW NET',
            'cloud' => 'Cloud Computing & Colocation',
            'lainnya' => 'Pertanyaan Umum / Kemitraan',
        ];

        TelegramNotifier::send(
            "📩 <b>Pesan Baru dari Form Hubungi Kami</b>\n\n" .
            "👤 Nama: {$submission->name}\n" .
            "📧 Email: {$submission->email}\n" .
            "📱 Telepon: {$submission->phone}\n" .
            "🏷️ Kategori: " . ($categoryLabels[$submission->category] ?? $submission->category) . "\n" .
            ($submission->message ? "📝 Pesan: {$submission->message}\n" : '') .
            "\n🕐 " . $submission->created_at->translatedFormat('d F Y, H:i')
        );

        $this->reset(['name', 'email', 'phone', 'message']);
        $this->category = 'instansi';
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}