<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Admin)
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <div class="text-center mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:underline font-bold text-sm"
            wire:navigate>
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Halaman Utama
        </a>
    </div>

    <form wire:submit.prevent="authenticate">
        @csrf

        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" wire:model.live="username"
            iconClass="fa-solid fa-user" required autofocus />
        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" wire:model.live="password"
            iconClass="fa-solid fa-lock" passwordToggle required />

        <div class="text-right text-sm text-gray-600 mb-6 pr-4">
            Lupa Kata Sandi? <a href="#" class="text-blue-600 hover:underline font-bold">Ubah Kata Sandi</a>
        </div>

        <div class="flex justify-between gap-x-3 mb-6">
            <x-form.button type="submit" label="Masuk Admin" class="flex-1 bg-[#0f498b] hover:bg-blue-800" />
        </div>

        <div class="text-center text-sm text-gray-600">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold"
                wire:navigate>Daftar
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
