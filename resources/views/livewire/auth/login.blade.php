<div class="w-full max-w-md mx-auto">
    <div class="bg-surface-container-lowest rounded-3xl shadow-xl p-8">
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-sakamedia.png') }}" alt="Saka Media" class="h-10 w-auto mx-auto mb-4">
            <h1 class="font-headline-sm text-headline-sm text-on-surface font-bold">Masuk ke Akun Anda</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Pantau status pendaftaran pemasangan internet Anda</p>
        </div>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Email</label>
                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="nama@email.com" autofocus>
                @error('email') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Password</label>
                <input wire:model="password" type="password" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="••••••••">
                @error('password') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant cursor-pointer">
                <input wire:model="remember" type="checkbox" class="rounded border-outline-variant text-primary focus:ring-primary/20">
                Ingat saya
            </label>

            <button type="submit" class="w-full px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep transition-all flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="login">Masuk</span>
                <span wire:loading wire:target="login" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            </button>
        </form>

        <p class="text-center font-body-sm text-body-sm text-on-surface-variant mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>