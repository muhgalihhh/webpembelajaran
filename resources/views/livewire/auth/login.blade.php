<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <form>
        @csrf

        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" wire:model.live="username"
            iconClass="fa-solid fa-user" required autofocus />
        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" wire:model.live="password"
            iconClass="fa-solid fa-lock" passwordToggle required />

        <div class="text-right text-sm text-gray-600 mb-6 pr-4">
            Reset Kata Sandi? <a href="#" class="text-blue-600 hover:underline font-bold">Reset</a>
        </div>

        <div class="flex justify-between gap-x-3 mb-6">
            <x-form.button type="button" wire:click="authenticateStudent" label="Masuk Akun Siswa"
                class="flex-1 bg-[#4A90E2]" />
            <x-form.button type="button" wire:click="authenticateTeacher" label="Masuk Akun Guru"
                class="flex-1 bg-[#0651a7]" />
        </div>

        <div class="text-center text-sm text-gray-600">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold"
                wire:navigate>Daftar
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
