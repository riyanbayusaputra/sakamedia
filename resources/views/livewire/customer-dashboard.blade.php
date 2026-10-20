@php
    $counts = $this->counts;
    $subscriptions = $this->subscriptions;

    $statusStyles = [
        'baru' => ['label' => 'Baru', 'icon' => 'fiber_new', 'badge' => 'bg-rose-50 text-rose-700 ring-rose-200'],
        'diproses' => ['label' => 'Diproses', 'icon' => 'event', 'badge' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'dijadwalkan' => ['label' => 'Dijadwalkan', 'icon' => 'event', 'badge' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'disurvei' => ['label' => 'Disurvei', 'icon' => 'fact_check', 'badge' => 'bg-sky-50 text-sky-700 ring-sky-200'],
        'pending' => ['label' => 'Pending', 'icon' => 'hourglass_top', 'badge' => 'bg-sky-50 text-sky-700 ring-sky-200'],
        'terpasang' => ['label' => 'Terpasang', 'icon' => 'check_circle', 'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'cancel', 'badge' => 'bg-slate-100 text-slate-600 ring-slate-200'],
    ];

    $filters = [
        'semua' => ['label' => 'Semua', 'icon' => 'groups', 'tone' => 'text-primary bg-surface-accent'],
        'baru' => ['label' => 'Baru', 'icon' => 'fiber_new', 'tone' => 'text-rose-600 bg-rose-50'],
        'proses' => ['label' => 'Dalam Proses', 'icon' => 'pending_actions', 'tone' => 'text-amber-600 bg-amber-50'],
        'terpasang' => ['label' => 'Terpasang', 'icon' => 'check_circle', 'tone' => 'text-emerald-600 bg-emerald-50'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'cancel', 'tone' => 'text-slate-500 bg-slate-100'],
    ];

    $steps = ['Baru', 'Dijadwalkan', 'Disurvei', 'Terpasang'];
    $stepIndexMap = ['baru' => 0, 'pending' => 0, 'diproses' => 1, 'dijadwalkan' => 1, 'disurvei' => 2, 'terpasang' => 3];
@endphp

<div class="w-full">

    {{-- Banner sapaan --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-deep to-primary text-on-primary p-6 sm:p-8 mb-6 shadow-lg">
        <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-cyan-speed/20 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div>
                <p class="font-label-md text-label-md text-primary-fixed-dim uppercase tracking-wider">Dashboard Sales</p>
                <h1 class="text-headline-lg-mobile sm:text-headline-lg font-extrabold mt-1">
                    Halo, {{ auth()->user()->name }} 👋
                </h1>
                <p class="font-body-md text-body-md text-on-primary-container mt-1">
                    Pantau perkembangan pelanggan yang sudah Anda daftarkan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ url('/#produk-pilihan') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full font-label-lg text-label-lg font-bold text-on-secondary-container bg-secondary-container hover:bg-secondary-fixed transition-all">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>Daftarkan Pelanggan
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-full font-label-lg text-label-lg font-bold text-on-primary border border-white/40 hover:bg-white/10 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kartu ringkasan (juga berfungsi sebagai filter) --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @foreach ($filters as $key => $meta)
            <button
                type="button"
                wire:click="setFilter(@js($key))"
                @class([
                    'rounded-2xl bg-surface-container-lowest p-4 text-left shadow-sm hover:shadow-md transition-all',
                    'col-span-2 sm:col-span-1' => $key === 'semua',
                    'ring-2 ring-primary' => $filter === $key,
                    'ring-1 ring-outline-variant/30' => $filter !== $key,
                ])
            >
                <span class="w-9 h-9 rounded-xl flex items-center justify-center {{ $meta['tone'] }}">
                    <span class="material-symbols-outlined text-[20px]">{{ $meta['icon'] }}</span>
                </span>
                <p class="text-headline-md font-extrabold text-on-surface mt-3 leading-none">{{ $counts[$key] }}</p>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $meta['label'] }}</p>
            </button>
        @endforeach
    </div>

    {{-- Pencarian --}}
    <div class="relative mb-5">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Cari nama, nomor telepon, atau alamat..."
            class="w-full pl-12 pr-4 py-3 rounded-full bg-surface-container-lowest text-on-surface border border-outline-variant/40 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
        >
    </div>

    {{-- Daftar pelanggan --}}
    <div wire:loading.class="opacity-60" wire:target="search,setFilter" class="transition-opacity">
        @forelse ($subscriptions as $sub)
            @php
                $style = $statusStyles[$sub->status] ?? ['label' => ucfirst($sub->status), 'icon' => 'info', 'badge' => 'bg-slate-100 text-slate-600 ring-slate-200'];
                $stepIndex = $stepIndexMap[$sub->status] ?? 0;
                $accent = $sub->status === 'terpasang' ? 'bg-emerald-500' : 'bg-primary';

                $digits = preg_replace('/\D+/', '', $sub->phone);
                if (str_starts_with($digits, '0')) {
                    $digits = '62' . substr($digits, 1);
                } elseif (str_starts_with($digits, '8')) {
                    $digits = '62' . $digits;
                }
            @endphp

            <div wire:key="sub-{{ $sub->id }}" class="bg-surface-container-lowest rounded-2xl shadow-sm hover:shadow-md ring-1 ring-outline-variant/20 p-5 sm:p-6 mb-4 transition-all">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-container text-on-primary flex items-center justify-center font-extrabold text-title-md shrink-0">
                            {{ mb_strtoupper(mb_substr($sub->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface font-bold truncate">{{ $sub->name }}</h3>
                            <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full bg-surface-accent text-primary font-label-md text-label-md font-bold">
                                <span class="material-symbols-outlined text-[14px]">wifi</span>{{ $sub->product_name }}
                            </span>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full ring-1 ring-inset font-label-md text-label-md font-bold whitespace-nowrap shrink-0 {{ $style['badge'] }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $style['icon'] }}</span>{{ $style['label'] }}
                    </span>
                </div>

                <div class="mt-4 space-y-1.5 font-body-sm text-body-sm text-on-surface-variant">
                    <p class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">call</span>{{ $sub->phone }}
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">location_on</span>{{ $sub->installation_address }}
                    </p>
                    <p class="flex items-start gap-2 text-text-muted">
                        <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">schedule</span>
                        Didaftarkan {{ $sub->created_at->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>

                {{-- Progres pemasangan --}}
                @if ($sub->status === 'dibatalkan')
                    <div class="mt-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-body-sm text-body-sm">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>Pendaftaran ini dibatalkan.
                    </div>
                @else
                    <div class="relative mt-5">
                        <div class="absolute top-[9px] left-[12.5%] right-[12.5%] h-0.5 bg-slate-200"></div>
                        <div class="absolute top-[9px] left-[12.5%] h-0.5 {{ $accent }} transition-all" style="width: {{ ($stepIndex / 3) * 75 }}%"></div>

                        <div class="relative grid grid-cols-4">
                            @foreach ($steps as $i => $label)
                                <div class="flex flex-col items-center gap-1.5">
                                    <span @class([
                                        'w-5 h-5 rounded-full flex items-center justify-center text-white',
                                        $accent => $i <= $stepIndex,
                                        'bg-slate-200' => $i > $stepIndex,
                                        'ring-4 ring-primary/20' => $i === $stepIndex && $sub->status !== 'terpasang',
                                    ])>
                                        @if ($i < $stepIndex || ($i === $stepIndex && $sub->status === 'terpasang'))
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                        @endif
                                    </span>
                                    <span @class([
                                        'text-[11px] leading-tight text-center',
                                        'font-bold text-on-surface' => $i === $stepIndex,
                                        'text-on-surface-variant' => $i !== $stepIndex,
                                    ])>{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($sub->status === 'pending')
    <p class="mt-3 flex items-center gap-2 px-4 py-3 rounded-xl bg-sky-50 text-sky-700 font-body-sm text-body-sm">
        <span class="material-symbols-outlined text-[18px]">hourglass_top</span>
        Menunggu konfirmasi pelanggan sebelum dijadwalkan.
    </p>
@endif

                {{-- Teknisi & jadwal --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1 mt-5 pt-4 border-t border-outline-variant/30 font-body-sm text-body-sm text-on-surface-variant">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">engineering</span>
                        @if ($sub->technician)
                            Teknisi: <strong class="text-on-surface">{{ $sub->technician->name }}</strong>
                        @else
                            Menunggu penugasan teknisi
                        @endif
                    </span>

                    @if ($sub->survey_date)
                        <span class="inline-flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">event</span>
                            Survei: <strong class="text-on-surface">{{ $sub->survey_date->translatedFormat('d M Y') }}</strong>
                        </span>
                    @endif
                </div>

                @if ($sub->notes)
                    <p class="mt-3 px-4 py-3 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-on-surface-variant">
                        <strong>Catatan:</strong> {{ $sub->notes }}
                    </p>
                @endif

                {{-- Aksi cepat --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="tel:{{ $sub->phone }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-primary bg-surface-accent hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[16px]">call</span>Telepon
                    </a>
                    <a href="https://wa.me/{{ $digits }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">chat</span>WhatsApp
                    </a>
                    @if ($sub->maps_url)
                        <a href="{{ $sub->maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-primary bg-surface-accent hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[16px]">map</span>Lihat Lokasi
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm ring-1 ring-outline-variant/20 p-10 text-center">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant">
                    {{ ($counts['semua'] ?? 0) === 0 ? 'inbox' : 'search_off' }}
                </span>

                @if (($counts['semua'] ?? 0) === 0)
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Anda belum mendaftarkan pelanggan mana pun.</p>
                    <a href="{{ url('/#produk-pilihan') }}" class="inline-flex items-center gap-1 mt-3 font-label-md text-label-md text-primary font-bold hover:underline">
                        <span>Daftarkan Pelanggan Pertama</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                @else
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Tidak ada pelanggan yang cocok dengan filter atau pencarian ini.</p>
                    <button type="button" wire:click="$set('search', ''); setFilter('semua')" class="inline-flex items-center gap-1 mt-3 font-label-md text-label-md text-primary font-bold hover:underline">
                        Reset filter
                    </button>
                @endif
            </div>
        @endforelse
    </div>
</div>