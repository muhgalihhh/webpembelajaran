<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Siswa atau Guru)
        <div class="mt-1 text-sm font-normal">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline"
        wire:navigate>
        <i class="mr-2 fa-solid fa-arrow-left"></i>
        Kembali ke Halaman Utama
    </a>

    <form wire:submit="login('siswa')" class="mt-6">
        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" wireModel="username" autofocus
            label="Username" icon="fa-solid fa-user" />

        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" wireModel="password"
            passwordToggle label="Kata Sandi" icon="fa-solid fa-lock" />

        <div class="pr-4 mb-6 text-sm text-right text-gray-600">
            Lupa Kata Sandi? <a href="{{ route('password.request') }}" class="font-bold text-blue-600 hover:underline"
                wire:navigate>Ubah Kata Sandi</a>
        </div>

        <div class="flex justify-between mb-6 gap-x-3">
            <button type="button" wire:click="login('siswa')"
                class="flex-1 bg-[#4A90E2] hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="login('siswa')">Masuk Akun Siswa</span>
                <span wire:loading wire:target="login('siswa')">
                    <i class="mr-2 fa fa-spinner fa-spin"></i>Memproses...
                </span>
            </button>

            <button type="button" wire:click="login('guru')"
                class="flex-1 bg-[#0651a7] hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="login('guru')">Masuk Akun Guru</span>
                <span wire:loading wire:target="login('guru')">
                    <i class="mr-2 fa fa-spinner fa-spin"></i>Memproses...
                </span>
            </button>
        </div>

        <div class="text-sm text-center text-gray-600">
            Belum Punya Akun? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline"
                wire:navigate>Daftar Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
