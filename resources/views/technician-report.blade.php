<x-layouts.app title="Formulir Pemasangan - Saka Media">
    <livewire:header />
    <main class="w-full pt-[110px] md:pt-[140px] pb-16 bg-surface min-h-screen">
        <div class="max-w-3xl mx-auto px-6">
            <livewire:technician-report-form :subscription="$subscription" />
        </div>
    </main>
    <livewire:footer />
</x-layouts.app>