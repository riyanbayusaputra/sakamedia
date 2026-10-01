@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-extrabold">
                Halo, {{ auth()->user()->name }} 👋
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                Berikut daftar pelanggan yang sudah Anda daftarkan untuk pemasangan.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ url('/#produk-pilihan') }}" class="px-5 py-2.5 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep transition-all">
                + Daftarkan Pelanggan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-full font-label-lg text-label-lg font-bold text-primary border border-primary hover:bg-surface-accent transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    @php
        $statusStyles = [
            'baru' => ['label' => 'Baru', 'class' => 'bg-error-container text-on-error-container'],
            'diproses' => ['label' => 'Diproses', 'class' => 'bg-gold-light text-gold-dark'],
            'disurvei' => ['label' => 'Sedang Disurvei', 'class' => 'bg-gold-light text-gold-dark'],
            'terpasang' => ['label' => 'Terpasang', 'class' => 'bg-surface-accent text-primary'],
            'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-surface-container text-on-surface-variant'],
        ];
    @endphp

    @forelse ($this->subscriptions as $subscription)
        <div wire:key="sub-{{ $subscription->id }}" class="bg-surface-container-lowest rounded-2xl shadow-sm p-6 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex gap-4">
                    @if ($subscription->photo)
                        <img src="{{ Storage::url($subscription->photo) }}" alt="Foto lokasi" class="w-16 h-16 rounded-xl object-cover shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-surface-accent flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-[28px]">person</span>
                        </div>
                    @endif

                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface font-bold">{{ $subscription->name }}</h3>
                        <p class="font-body-sm text-body-sm text-primary font-semibold mt-0.5">{{ $subscription->product_name }}</p>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 font-body-sm text-body-sm text-on-surface-variant">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">call</span>{{ $subscription->phone }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">mail</span>{{ $subscription->email }}</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $subscription->installation_address }}</p>
                        <p class="font-body-sm text-body-sm text-text-muted mt-1">
                            Didaftarkan pada {{ $subscription->created_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                </div>

                <span class="px-3 py-1.5 rounded-full font-label-md text-label-md font-bold whitespace-nowrap {{ $statusStyles[$subscription->status]['class'] ?? 'bg-surface-container text-on-surface-variant' }}">
                    {{ $statusStyles[$subscription->status]['label'] ?? ucfirst($subscription->status) }}
                </span>
            </div>

            @if ($subscription->notes)
                <div class="mt-4 pt-4 border-t border-outline-variant/30">
                    <p class="font-body-sm text-body-sm text-on-surface-variant"><strong>Catatan:</strong> {{ $subscription->notes }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-10 text-center">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-3">inbox</span>
            <p class="font-body-md text-body-md text-on-surface-variant">Anda belum mendaftarkan pelanggan mana pun.</p>
            <a href="{{ url('/#produk-pilihan') }}" class="inline-flex items-center gap-1 mt-3 font-label-md text-label-md text-primary font-bold hover:underline">
                <span>Daftarkan Pelanggan Pertama</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    @endforelse
</div>