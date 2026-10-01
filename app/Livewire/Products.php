<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Subscription;
use App\Services\TelegramNotifier;
use Livewire\Component;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithFileUploads;

    public string $activeCategory = 'semua';

    public array $categories = [
        'semua' => 'Semua Paket',
        'rumah' => 'Internet Rumah & Wi-Fi',
        'gamer' => 'Paket Gamer & Streaming',
        'bisnis' => 'Dedicated Instansi & Bisnis',
    ];

    public bool $showModal = false;
    public ?int $selectedProductId = null;
    public string $selectedProductName = '';

    public string $subName = '';
    public string $subEmail = '';
    public string $subPhone = '';
    public string $subAddress = '';
    public string $subNotes = '';
    public $subPhoto = null;

    public bool $subSubmitted = false;

    public function setCategory(string $category)
    {
        $this->activeCategory = $category;
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->when(
                $this->activeCategory !== 'semua',
                fn ($query) => $query->where('category', $this->activeCategory)
            )
            ->orderBy('sort_order')
            ->get();
    }

    public function openModal(int $productId, string $productName)
    {
        abort_unless(
            auth()->check(),
            403,
            'Anda harus login sebagai sales/teknisi untuk mendaftarkan pelanggan.'
        );

        $this->selectedProductId = $productId;
        $this->selectedProductName = $productName;
        $this->subSubmitted = false;

        $this->reset([
            'subName',
            'subEmail',
            'subPhone',
            'subAddress',
            'subNotes',
            'subPhoto'
        ]);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;

        $this->reset([
            'subName',
            'subEmail',
            'subPhone',
            'subAddress',
            'subNotes',
            'subPhoto',
            'subSubmitted'
        ]);
    }

    public function removePhoto()
    {
        $this->subPhoto = null;
    }

    public function submitSubscription()
    {
        abort_unless(auth()->check(), 403);

        $validated = $this->validate([
            'subName' => 'required|min:3',
            'subEmail' => 'required|email',
            'subPhone' => 'required|min:9',
            'subAddress' => 'required|min:10',
            'subNotes' => 'nullable|string',
            'subPhoto' => 'nullable|image|max:5120',
        ], [
            'subName.required' => 'Nama pelanggan wajib diisi.',
            'subName.min' => 'Nama terlalu pendek.',
            'subEmail.required' => 'Email pelanggan wajib diisi.',
            'subEmail.email' => 'Format email tidak valid.',
            'subPhone.required' => 'Nomor telepon pelanggan wajib diisi.',
            'subPhone.min' => 'Nomor telepon terlalu pendek.',
            'subAddress.required' => 'Alamat pemasangan wajib diisi.',
            'subAddress.min' => 'Mohon tulis alamat lebih lengkap.',
            'subPhoto.image' => 'File harus berupa gambar (jpg, png, dll).',
            'subPhoto.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $photoPath = $this->subPhoto
            ? $this->subPhoto->store('subscriptions', 'public')
            : null;

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'product_id' => $this->selectedProductId,
            'product_name' => $this->selectedProductName,
            'name' => $validated['subName'],
            'email' => $validated['subEmail'],
            'phone' => $validated['subPhone'],
            'installation_address' => $validated['subAddress'],
            'photo' => $photoPath,
            'notes' => $validated['subNotes'] ?: null,
            'status' => 'baru',
        ]);

     
        TelegramNotifier::send(
            "🔔 <b>Pendaftaran Pemasangan Baru</b>\n\n" .
            "📦 Paket: <b>{$subscription->product_name}</b>\n" .
            "👤 Pelanggan: {$subscription->name}\n" .
            "📧 Email: {$subscription->email}\n" .
            "📱 Telepon: {$subscription->phone}\n" .
            "📍 Alamat: {$subscription->installation_address}\n" .
            ($subscription->notes ? "📝 Catatan: {$subscription->notes}\n" : '') .
            "🧑‍💼 Diinput oleh: " . auth()->user()->name . "\n" .
            "\n🕐 " . $subscription->created_at->translatedFormat('d F Y, H:i')
        );
    

        $this->reset([
            'subName',
            'subEmail',
            'subPhone',
            'subAddress',
            'subNotes',
            'subPhoto'
        ]);

        $this->subSubmitted = true;
    }

    public function render()
    {
        return view('livewire.products');
    }
}