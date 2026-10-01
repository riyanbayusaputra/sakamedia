@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="w-full">

    {{-- Hero gambar sampul --}}
    <section  class="relative w-full h-64 md:h-96 bg-gradient-to-br from-violet-deep to-primary overflow-hidden">
        @if ($article->cover_image)
            <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-[#1D0626]/80 via-[#1D0626]/20 to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 max-w-4xl mx-auto px-6 lg:px-12 pb-8">
            <span class="inline-block px-3 py-1 rounded bg-primary text-on-primary font-label-md text-label-md font-semibold mb-3">{{ $article->category }}</span>
            <h1 class="font-headline-lg text-headline-lg md:text-display-xl-mobile text-surface-container-lowest font-extrabold leading-tight">{{ $article->title }}</h1>
        </div>
    </section>

    {{-- Meta info + tombol kembali --}}
    <div class="max-w-4xl mx-auto px-6 lg:px-12 py-8">
        <div class="flex items-center justify-between flex-wrap gap-4 pb-6 border-b border-outline-variant/30">
            <a href="{{ url('/') }}#berita" class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-semibold hover:underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span><span>Kembali ke Beranda</span>
            </a>
            <div class="flex items-center gap-4 text-body-sm font-body-sm text-on-surface-variant">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">person</span>{{ $article->author }}</span>
                @if ($article->published_at)
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">calendar_today</span>{{ $article->published_at->translatedFormat('d F Y') }}</span>
                @endif
            </div>
        </div>

        {{-- Ringkasan --}}
        <p class="font-title-md text-title-md text-on-surface-variant font-semibold mt-6 mb-4">{{ $article->excerpt }}</p>

        {{-- Isi lengkap artikel --}}
        <div class="font-body-lg text-body-lg text-on-surface leading-relaxed space-y-4">
            @if ($article->content)
                {!! nl2br(e($article->content)) !!}
            @else
                <p class="text-on-surface-variant italic">Konten lengkap artikel ini belum tersedia.</p>
            @endif
        </div>
    </div>

    {{-- Rekomendasi artikel lain --}}
    @if ($this->relatedArticles->isNotEmpty())
        <section class="w-full py-12 bg-surface-subtle">
            <div class="max-w-4xl mx-auto px-6 lg:px-12">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-6">Baca Juga</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach ($this->relatedArticles as $related)
                        <a href="{{ route('articles.show', $related->slug) }}" class="block rounded-2xl bg-surface-container-lowest overflow-hidden shadow-sm hover:shadow-md transition-all">
                            <div class="h-28 bg-gradient-to-br from-violet-deep to-primary relative">
                                @if ($related->cover_image)
                                    <img src="{{ Storage::url($related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <span class="text-body-sm font-body-sm text-primary font-semibold">{{ $related->category }}</span>
                                <h3 class="font-title-sm text-title-sm text-on-surface font-bold mt-1 line-clamp-2">{{ $related->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>