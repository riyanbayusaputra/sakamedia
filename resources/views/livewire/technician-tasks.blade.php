@php
    $tasks = $this->tasks;

    $doneToday = $tasks['reported_today'];
    $totalToday = $tasks['today']->count() + $doneToday;
    $percent = $totalToday > 0 ? (int) round($doneToday / $totalToday * 100) : 0;

    $sections = [
        'today' => [
            'id' => 'tugas-hari-ini',
            'title' => 'Tugas Hari Ini',
            'icon' => 'today',
            'count' => 'bg-surface-accent text-primary',
            'accent' => 'border-l-primary',
            'chip' => 'bg-surface-accent text-primary ring-primary/20',
            'always' => true,
        ],
        'overdue' => [
            'id' => 'terlewat',
            'title' => 'Terlewat',
            'icon' => 'warning',
            'count' => 'bg-rose-50 text-rose-700',
            'accent' => 'border-l-rose-500',
            'chip' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'always' => false,
        ],
        'upcoming' => [
            'id' => 'mendatang',
            'title' => 'Mendatang',
            'icon' => 'event',
            'count' => 'bg-slate-100 text-slate-600',
            'accent' => 'border-l-slate-300',
            'chip' => 'bg-slate-100 text-slate-600 ring-slate-200',
            'always' => false,
        ],
    ];

    $summary = [
        ['label' => 'Hari Ini', 'value' => $tasks['today']->count(), 'icon' => 'today', 'tone' => 'text-primary bg-surface-accent', 'href' => '#tugas-hari-ini'],
        ['label' => 'Terlewat', 'value' => $tasks['overdue']->count(), 'icon' => 'warning', 'tone' => 'text-rose-600 bg-rose-50', 'href' => '#terlewat'],
        ['label' => 'Mendatang', 'value' => $tasks['upcoming']->count(), 'icon' => 'event', 'tone' => 'text-amber-600 bg-amber-50', 'href' => '#mendatang'],
        ['label' => 'Dilaporkan Hari Ini', 'value' => $doneToday, 'icon' => 'fact_check', 'tone' => 'text-emerald-600 bg-emerald-50', 'href' => '#sudah-dilaporkan'],
    ];
@endphp

<div class="w-full">

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200 flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px]">check_circle</span>
            <p class="font-body-md text-body-md font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Banner sapaan + progres hari ini --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-deep to-primary text-on-primary p-6 sm:p-8 mb-6 shadow-lg">
        <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-cyan-speed/20 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div>
                <p class="font-label-md text-label-md text-primary-fixed-dim uppercase tracking-wider">Dashboard Teknisi</p>
                <h1 class="text-headline-lg-mobile sm:text-headline-lg font-extrabold mt-1">
                    Halo, {{ auth()->user()->name }} 👷
                </h1>
                <p class="font-body-md text-body-md text-on-primary-container mt-1">
                    {{ now()->translatedFormat('l, d F Y') }}
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

        <div class="relative mt-6 rounded-2xl bg-white/10 backdrop-blur-sm p-4">
            @if ($totalToday > 0)
                <div class="flex items-center justify-between gap-3 mb-2">
                    <p class="font-label-lg text-label-lg font-bold">Progres hari ini</p>
                    <p class="font-label-md text-label-md text-on-primary-container">{{ $doneToday }} dari {{ $totalToday }} tugas dilaporkan</p>
                </div>
                <div class="h-2.5 rounded-full bg-white/20 overflow-hidden">
                    <div class="h-full rounded-full bg-secondary-container transition-all" style="width: {{ $percent }}%"></div>
                </div>
            @else
                <p class="font-body-md text-body-md text-on-primary-container flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">coffee</span>
                    Belum ada tugas untuk hari ini.
                </p>
            @endif
        </div>
    </div>

    {{-- Kartu ringkasan --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        @foreach ($summary as $card)
            <a href="{{ $card['href'] }}" class="rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md ring-1 ring-outline-variant/30 transition-all">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center {{ $card['tone'] }}">
                    <span class="material-symbols-outlined text-[20px]">{{ $card['icon'] }}</span>
                </span>
                <p class="text-headline-md font-extrabold text-on-surface mt-3 leading-none">{{ $card['value'] }}</p>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $card['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Daftar tugas --}}
    @foreach ($sections as $key => $section)
        @if ($tasks[$key]->isNotEmpty() || $section['always'])
            <div id="{{ $section['id'] }}" class="mb-8 scroll-mt-28">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[22px] text-primary">{{ $section['icon'] }}</span>
                    <h2 class="font-title-md text-title-md text-on-surface font-bold">{{ $section['title'] }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full font-label-md text-label-md font-bold {{ $section['count'] }}">
                        {{ $tasks[$key]->count() }}
                    </span>
                </div>

                @forelse ($tasks[$key] as $task)
                    @php
                        $digits = preg_replace('/\D+/', '', $task->phone);
                        if (str_starts_with($digits, '0')) {
                            $digits = '62' . substr($digits, 1);
                        } elseif (str_starts_with($digits, '8')) {
                            $digits = '62' . $digits;
                        }

                        // Pakai titik koordinat kalau sudah ada, kalau belum cari lewat alamat
                        $mapsUrl = $task->maps_url
                            ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($task->installation_address);

                        if ($key === 'today') {
                            $chipText = 'Hari ini';
                        } elseif ($key === 'overdue') {
                            $days = (int) $task->survey_date->diffInDays(today(), true);
                            $chipText = 'Terlambat ' . $days . ' hari';
                        } else {
                            $chipText = $task->survey_date
                                ? $task->survey_date->translatedFormat('d M Y')
                                : 'Tanggal belum ditentukan';
                        }
                    @endphp

                    <div wire:key="task-{{ $task->id }}" class="bg-surface-container-lowest rounded-2xl shadow-sm hover:shadow-md ring-1 ring-outline-variant/20 border-l-4 {{ $section['accent'] }} p-5 sm:p-6 mb-4 transition-all">

                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-4 min-w-0">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-container text-on-primary flex items-center justify-center font-extrabold text-title-md shrink-0">
                                    {{ mb_strtoupper(mb_substr($task->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <h3 class="font-title-md text-title-md text-on-surface font-bold truncate">{{ $task->name }}</h3>
                                    <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full bg-surface-accent text-primary font-label-md text-label-md font-bold">
                                        <span class="material-symbols-outlined text-[14px]">wifi</span>{{ $task->product_name }}
                                    </span>
                                </div>
                            </div>

                            <span class="px-3 py-1.5 rounded-full ring-1 ring-inset font-label-md text-label-md font-bold whitespace-nowrap shrink-0 {{ $section['chip'] }}">
                                {{ $chipText }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-1.5 font-body-sm text-body-sm text-on-surface-variant">
                            <p class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">call</span>{{ $task->phone }}
                            </p>
                            <p class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">location_on</span>{{ $task->installation_address }}
                            </p>
                            <p class="flex items-start gap-2 text-text-muted">
                                <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">support_agent</span>
                                @if ($task->user_id === auth()->id())
                                    Didaftarkan sendiri
                                @elseif ($task->user)
                                    Sales: {{ $task->user->name }}
                                @else
                                    Pendaftaran publik
                                @endif
                            </p>
                        </div>

                        @if ($task->notes)
                            <p class="mt-3 px-4 py-3 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-on-surface-variant">
                                <strong>Catatan:</strong> {{ $task->notes }}
                            </p>
                        @endif

                        <div class="flex flex-wrap gap-2 mt-4">
                            <a href="tel:{{ $task->phone }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-primary bg-surface-accent hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">call</span>Telepon
                            </a>
                            <a href="https://wa.me/{{ $digits }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">chat</span>WhatsApp
                            </a>
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-primary bg-surface-accent hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">map</span>Maps
                            </a>
                        </div>

                        <a href="{{ route('technician.report', $task) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep shadow-md hover:shadow-lg transition-all">
                            <span class="material-symbols-outlined text-[18px]">assignment</span>Isi Formulir Pemasangan
                        </a>
                    </div>
                @empty
                    <div class="bg-surface-container-lowest rounded-2xl shadow-sm ring-1 ring-outline-variant/20 p-8 text-center">
                        @if ($doneToday > 0)
                            <span class="material-symbols-outlined text-[44px] text-emerald-500">celebration</span>
                            <p class="font-title-sm text-title-sm text-on-surface font-bold mt-2">Semua tugas hari ini sudah dilaporkan 🎉</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Kerja bagus!</p>
                        @else
                            <span class="material-symbols-outlined text-[44px] text-on-surface-variant">task_alt</span>
                            <p class="font-body-md text-body-md text-on-surface-variant mt-2">Belum ada tugas survei untuk hari ini.</p>
                            <a href="{{ url('/#produk-pilihan') }}" class="inline-flex items-center gap-1 mt-3 font-label-md text-label-md text-primary font-bold hover:underline">
                                <span>Daftarkan pelanggan baru</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        @endif
    @endforeach

    {{-- Menunggu konfirmasi pelanggan (hanya dilihat) --}}
@if ($tasks['awaiting']->isNotEmpty())
    <div id="menunggu-konfirmasi" class="mb-8 scroll-mt-28">
        <div class="flex items-center gap-2 mb-1">
            <span class="material-symbols-outlined text-[22px] text-sky-600">hourglass_top</span>
            <h2 class="font-title-md text-title-md text-on-surface font-bold">Menunggu Konfirmasi</h2>
            <span class="px-2.5 py-0.5 rounded-full font-label-md text-label-md font-bold bg-sky-50 text-sky-700">
                {{ $tasks['awaiting']->count() }}
            </span>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">
            Pelanggan ini belum dikonfirmasi. Tugas masuk daftar aktif setelah admin menjadwalkannya.
        </p>

        @foreach ($tasks['awaiting'] as $task)
            <div wire:key="awaiting-{{ $task->id }}" class="bg-surface-container-lowest rounded-2xl shadow-sm ring-1 ring-outline-variant/20 border-l-4 border-l-sky-400 p-4 sm:p-5 mb-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center font-extrabold shrink-0">
                            {{ mb_strtoupper(mb_substr($task->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-title-sm text-title-sm text-on-surface font-bold truncate">{{ $task->name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $task->product_name }}</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex items-start gap-1">
                                <span class="material-symbols-outlined text-[14px] mt-0.5 shrink-0">location_on</span>
                                <span>{{ $task->installation_address }}</span>
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full ring-1 ring-inset bg-sky-50 text-sky-700 ring-sky-200 font-label-md text-label-md font-bold whitespace-nowrap shrink-0">
                        <span class="material-symbols-outlined text-[16px]">hourglass_top</span>Pending
                    </span>
                </div>

                @if ($task->survey_date)
                    <p class="font-body-sm text-body-sm text-text-muted mt-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">event</span>
                        Rencana survei: {{ $task->survey_date->translatedFormat('d M Y') }}
                    </p>
                @endif
            </div>
        @endforeach
    </div>
@endif

    {{-- Sudah dilaporkan --}}
    @if ($tasks['reported']->isNotEmpty())
        <div id="sudah-dilaporkan" class="mb-8 scroll-mt-28">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[22px] text-emerald-600">fact_check</span>
                <h2 class="font-title-md text-title-md text-on-surface font-bold">Sudah Dilaporkan</h2>
                <span class="px-2.5 py-0.5 rounded-full font-label-md text-label-md font-bold bg-emerald-50 text-emerald-700">
                    {{ $tasks['reported']->count() }}
                </span>
            </div>

            @foreach ($tasks['reported'] as $task)
                <div wire:key="reported-{{ $task->id }}" class="bg-surface-container-lowest rounded-2xl shadow-sm ring-1 ring-outline-variant/20 border-l-4 border-l-emerald-500 p-4 sm:p-5 mb-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">check_circle</span>
                        </span>
                        <div class="min-w-0">
                            <h3 class="font-title-sm text-title-sm text-on-surface font-bold truncate">{{ $task->name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant truncate">
                                {{ $task->product_name }} · {{ $task->surveyed_at?->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if ($task->maps_url)
                            <a href="{{ $task->maps_url }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-surface-accent text-primary hover:bg-surface-container flex items-center justify-center transition-colors" aria-label="Lihat lokasi">
                                <span class="material-symbols-outlined text-[18px]">map</span>
                            </a>
                        @endif
                        <a href="{{ route('technician.report', $task) }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-label-md text-label-md font-bold text-primary bg-surface-accent hover:bg-surface-container whitespace-nowrap transition-colors">
                            <span class="material-symbols-outlined text-[16px]">edit</span>Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>