<x-ui.auth-card>
    <x-ui.auth-header>
        Lupa Kata Sandi
        <div class="mt-1 text-sm font-normal">Masukkan email Anda untuk menerima tautan reset.</div>
    </x-ui.auth-header>

    @if ($emailSentMessage)
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
            <p class="font-bold">Tautan Terkirim!</p>
            <p>{{ $emailSentMessage }} Periksa kotak masuk dan folder spam Anda.</p>
        </div>
    @endif

    <form wire:submit="sendResetLink">
        @if (!$emailSentMessage)
            <x-form.input-group type="email" id="email" placeholder="cth: siswa@email.com" wireModel="email" autofocus
                label="Alamat Email Terdaftar" icon="fa-solid fa-envelope" />

            <div class="mt-8">
                <button type="submit" wire:loading.attr="disabled"
                    class="flex items-center justify-center w-full bg-[#4A90E2] hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                    <span wire:loading.remove wire:target="sendResetLink">Kirim Tautan Reset</span>
                    <span wire:loading wire:target="sendResetLink">
                        <i class="mr-2 fa fa-spinner fa-spin"></i>Mengirim...
                    </span>
                </button>
            </div>
        @endif
    </form>

    <div class="mt-6 text-sm text-center">
        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline" wire:navigate>
            <i class="mr-1 fa-solid fa-arrow-left"></i> Kembali untuk Masuk
        </a>
    </div>
</x-ui.auth-card>
