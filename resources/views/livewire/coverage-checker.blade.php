<section class="relative -mt-8 z-20 max-w-5xl mx-auto px-6 w-full" id="cakupan">
    <div class="rounded-2xl p-6 lg:p-8 bg-surface-container-lowest shadow-xl" data-reveal="zoom">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px] float-y">my_location</span>
                </div>
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface font-bold">Cek Ketersediaan Jaringan Saka Media</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Pastikan lokasi gedung kantor, instansi, atau klaster perumahan Anda terjangkau Fiber Optic Saka Media</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 text-label-md font-label-md text-primary font-semibold">
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                    <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-500"></span>
                </span>Ready di 25+ Kecamatan Jateng
            </span>
        </div>

        <form wire:submit="checkCoverage" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4 relative">
                <div class="flex items-center px-4 py-3 rounded-xl bg-surface-container-low text-on-surface transition-shadow duration-300 focus-within:ring-2 focus-within:ring-primary/20">
                    <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2">apartment</span>
                    <select wire:model="region" class="w-full bg-transparent outline-none font-body-md text-body-md text-on-surface cursor-pointer">
                        <option value="pemalang">Kab. Pemalang (Pusat Randudongkal)</option>
                        <option value="tegal">Kota &amp; Kab. Tegal</option>
                        <option value="pekalongan">Kota &amp; Kab. Pekalongan</option>
                        <option value="banyumas">Kab. Banyumas / Purwokerto</option>
                        <option value="brebes">Kab. Brebes</option>
                        <option value="lainnya">Wilayah Lainnya di Jawa Tengah</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-5 relative">
                <div class="flex items-center px-4 py-3 rounded-xl bg-surface-container-low text-on-surface transition-shadow duration-300 focus-within:ring-2 focus-within:ring-primary/20">
                    <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2">search</span>
                    <input wire:model="address" type="text" class="w-full bg-transparent outline-none font-body-md text-body-md placeholder:text-on-surface-variant/70" placeholder="Tulis alamat jalan, instansi, atau RT/RW...">
                </div>
                @error('address')
                    <p class="pop-in text-error text-body-sm mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="w-full h-full min-h-[48px] px-6 py-3 rounded-xl font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all duration-300">
                    <span wire:loading.remove wire:target="checkCoverage" class="material-symbols-outlined text-[20px]">radar</span>
                    <span wire:loading wire:target="checkCoverage" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                    <span>Cek Ketersediaan</span>
                </button>
            </div>
        </form>

        @if ($checked)
            <div class="pop-in mt-4 p-4 rounded-xl bg-gold-light text-on-secondary-fixed flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-gold-dark text-[24px]">task_alt</span>
                    <p class="font-body-md text-body-md font-semibold">Area Anda terdeteksi dalam cakupan jalur Backbone Utama Saka Media! Tim kami siap survei lokasi.</p>
                </div>
                <a class="px-4 py-1.5 rounded-lg bg-gold-dark text-surface-container-lowest font-label-md text-label-md font-bold whitespace-nowrap hover:opacity-90 transition-opacity" href="#hubungi-kami">Hubungi Sales</a>
            </div>
        @endif
    </div>
</section>