<section id="article-hero" class="w-full py-16 bg-surface-subtle">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="px-3.5 py-1.5 rounded-full bg-surface-accent text-primary font-label-md text-label-md font-semibold uppercase tracking-wider">Wawasan &amp; Update</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mt-2">Berita, Event &amp; Tips Teknologi</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Berita, tips, dan praktik terbaik seputar dunia telekomunikasi dan komputasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($this->articles as $article)
                <article wire:key="article-{{ $article->slug }}" class="rounded-2xl bg-surface-container-lowest overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">

                    <div class="h-44 bg-gradient-to-br from-violet-deep to-primary relative overflow-hidden">
                        @if ($article->cover_image)
                            <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded bg-primary text-on-primary font-label-md text-label-md font-semibold">{{ $article->category }}</span>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-body-sm font-body-sm text-text-muted">
                                <span class="material-symbols-outlined text-[14px]">person</span><span>{{ $article->author }}</span>
                            </div>
                            <h3 class="font-title-sm text-title-sm text-on-surface font-bold hover:text-primary transition-colors line-clamp-2">{{ $article->title }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-3">{{ $article->excerpt }}</p>
                        </div>
                    <a href="{{ route('articles.show', $article->slug) }}" class="mt-4 inline-flex items-center gap-1 font-label-md text-label-md text-primary font-bold hover:underline">
                         <span>Baca Selengkapnya</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-10">Belum ada berita untuk saat ini.</p>
            @endforelse
        </div>
    </div>
</section>