<x-ui.auth-card>
    <x-ui.auth-header>
        Atur Ulang Kata Sandi Anda
    </x-ui.auth-header>

    <form wire:submit="resetPassword">
        <input type="hidden" wire:model="token">

        <x-form.input-group type="email" id="email" placeholder="Email Anda" wireModel="email" label="Alamat Email"
            icon="fa-solid fa-envelope" />

        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi Baru" wireModel="password"
            passwordToggle label="Kata Sandi Baru" icon="fa-solid fa-lock" />

        <x-form.input-group type="password" id="password_confirmation" placeholder="Konfirmasi Kata Sandi"
            wireModel="password_confirmation" label="Konfirmasi Kata Sandi" icon="fa-solid fa-lock" />

        <div class="mt-8">
            <button type="submit" wire:loading.attr="disabled"
                class="flex items-center justify-center w-full bg-[#4A90E2] hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="resetPassword">Atur Ulang Kata Sandi</span>
                <span wire:loading wire:target="resetPassword">
                    <i class="mr-2 fa fa-spinner fa-spin"></i>Memproses...
                </span>
            </button>
        </div>
    </form>
</x-ui.auth-card>
