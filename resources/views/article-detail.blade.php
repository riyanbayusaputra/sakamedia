<x-layouts.app title="Detail Berita - Saka Media">
    <livewire:header />

    <main class="w-full pt-[88px] md:pt-[120px] bg-surface">
        <livewire:article-detail :slug="$slug" />
    </main>

    <livewire:footer />
</x-layouts.app>