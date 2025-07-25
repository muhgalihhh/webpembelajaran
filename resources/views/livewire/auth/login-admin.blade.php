<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Admin)
        <div class="mt-1 text-sm font-normal">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline"
        wire:navigate>
        <i class="mr-2 fa-solid fa-arrow-left"></i>
        Kembali ke Halaman Utama
    </a>

    <form wire:submit="authenticate" class="mt-6">
        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" wireModel="username"
            icon="fa fa-user" autofocus />
        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" wireModel="password"
            icon="fa fa-lock" passwordToggle />



        <div class="flex justify-between mb-6 gap-x-3">
            <button type="submit"
                class="flex-1 bg-[#0651a7] hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="authenticate">Masuk Akun Admin</span>
                <span wire:loading wire:target="authenticate">
                    <i class="mr-2 fa fa-spinner fa-spin"></i>Memproses...
                </span>
            </button>
        </div>
    </form>
</x-ui.auth-card>
