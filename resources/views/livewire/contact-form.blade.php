<section class="w-full py-16 lg:py-24 bg-surface" id="hubungi-kami">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="rounded-3xl bg-surface-container-lowest p-8 lg:p-14 shadow-xl" data-reveal="zoom">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- Kolom kiri: info kontak --}}
                <div class="lg:col-span-5 space-y-6" data-reveal="left" style="--d:150">
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-md text-label-md font-bold uppercase tracking-wider">Layanan Konsultasi</span>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface font-extrabold mt-3">Bagaimana Kami Dapat Membantu Anda?</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-2 leading-relaxed">Silahkan kirimkan pertanyaan atau pesan Anda melalui formulir berikut dan tim konsultan jaringan kami akan segera menghubungi Anda kembali dalam hitungan jam.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-surface-container-low space-y-4">
                        <h3 class="font-title-sm text-title-sm font-bold text-on-surface">KANTOR PUSAT OPERASIONAL</h3>
                        <p class="font-label-lg text-label-lg font-bold text-primary">PT SAKA MEDIA KOMUNIKA</p>
                        <div class="space-y-3 font-body-md text-body-md text-on-surface-variant">
                            <div class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">location_on</span><span>Jl. Kalimas No.Rt. 024/02, Area Sawah / Kebun, Lodaya, Kec. Randudongkal, Kabupaten Pemalang, Jawa Tengah 52353</span></div>
                            <div class="flex items-center gap-3"><span class="material-symbols-outlined text-primary text-[20px] shrink-0">call</span><span class="font-semibold text-on-surface">+62 81510571114</span></div>
                            <div class="flex items-center gap-3"><span class="material-symbols-outlined text-primary text-[20px] shrink-0">mail</span><span>info@sakamedia.net</span></div>
                        </div>
                    </div>

                    <a class="group flex items-center justify-between p-4 rounded-2xl bg-emerald-50 text-emerald-900 hover:bg-emerald-100 hover:-translate-y-0.5 hover:shadow-md transition-all duration-300" href="https://wa.me/6281510571114" rel="noopener" target="_blank">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform duration-300"><span class="material-symbols-outlined text-[22px]">chat</span></div>
                            <div><p class="font-title-sm text-title-sm font-bold">Chat Langsung via WhatsApp</p><p class="font-body-sm text-body-sm text-emerald-700">Respon cepat Customer Service</p></div>
                        </div>
                        <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                    </a>
                </div>

                {{-- Kolom kanan: form + peta --}}
                <div class="lg:col-span-7" data-reveal="right" style="--d:300">
                    <form wire:submit="submit" class="space-y-4">

                        @if ($submitted)
                            <div class="pop-in p-4 rounded-xl bg-surface-accent text-primary flex items-center gap-3">
                                <span class="material-symbols-outlined text-[24px]">mark_email_read</span>
                                <p class="font-body-md text-body-md font-semibold">Pesan Anda telah kami terima, tim kami akan segera menghubungi Anda. Terima kasih!</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Nama Lengkap *</label>
                                <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none transition-shadow duration-300 focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Budi Santoso">
                                @error('name') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Alamat Email *</label>
                                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none transition-shadow duration-300 focus:ring-2 focus:ring-primary/20" placeholder="nama@instansi.co.id">
                                @error('email') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">No. Telepon / WhatsApp *</label>
                                <input wire:model="phone" type="tel" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none transition-shadow duration-300 focus:ring-2 focus:ring-primary/20" placeholder="0812-xxxx-xxxx">
                                @error('phone') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Kategori Layanan *</label>
                                <select wire:model="category" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none transition-shadow duration-300 focus:ring-2 focus:ring-primary/20 cursor-pointer">
                                    <option value="instansi">Dedicated Instansi (100M - 1Gbps)</option>
                                    <option value="rtrw">Dedicated Pengusaha / RT RW NET</option>
                                    <option value="cloud">Cloud Computing &amp; Colocation</option>
                                    <option value="lainnya">Pertanyaan Umum / Kemitraan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Kolom Pesan / Kebutuhan Bandwidth</label>
                            <textarea wire:model="message" rows="4" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none transition-shadow duration-300 focus:ring-2 focus:ring-primary/20" placeholder="Tuliskan detail kebutuhan koneksi, perkiraan jumlah user, atau alamat pemasangan..."></textarea>
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep hover:-translate-y-0.5 active:translate-y-0 shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="submit" class="material-symbols-outlined text-[20px]">send</span>
                            <span wire:loading wire:target="submit" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                            <span>Kirim Permintaan Konsultasi</span>
                        </button>
                    </form>

                    <div class="mt-8 pt-6">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-label-md text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Peta Kantor Pusat</p>
                        </div>
                        <div class="w-full h-56 rounded-2xl overflow-hidden shadow-inner border border-outline-variant/30">
                            <iframe
                                src="https://www.google.com/maps?q=PT+SAKA+MEDIA+KOMUNIKA,+Jl.+Kalimas+Randudongkal+Pemalang&ll=-7.08361,109.3120782&z=16&output=embed"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi PT Saka Media Komunika"
                            ></iframe>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>