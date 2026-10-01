<div class="w-full max-w-md mx-auto">
    <div class="bg-surface-container-lowest rounded-3xl shadow-xl p-8">
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-sakamedia.png') }}" alt="Saka Media" class="h-10 w-auto mx-auto mb-4">
            <h1 class="font-headline-sm text-headline-sm text-on-surface font-bold">Buat Akun Baru</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Daftar untuk memantau status pemasangan internet Anda</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Nama Lengkap</label>
                <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Budi Santoso" autofocus>
                @error('name') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Email</label>
                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="nama@email.com">
                @error('email') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Password</label>
                <input wire:model="password" type="password" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Minimal 8 karakter">
                @error('password') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Konfirmasi Password</label>
                <input wire:model="password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Ulangi password">
            </div>

            <button type="submit" class="w-full px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep transition-all flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                <span wire:loading wire:target="register" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            </button>
        </form>

        <p class="text-center font-body-sm text-body-sm text-on-surface-variant mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>