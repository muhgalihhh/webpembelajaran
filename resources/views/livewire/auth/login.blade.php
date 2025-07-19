<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Siswa atau Guru)
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:underline font-bold text-sm"
        wire:navigate>
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Kembali ke Halaman Utama
    </a>

    <form class="mt-6" wire:submit.prevent="login">
        @csrf

        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" model="username" icon="fa fa-user"
            required autofocus />
        @error('username')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" model="password"
            icon="fa fa-lock" passwordToggle required />
        @error('password')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror

        <div class="text-right text-sm text-gray-600 mb-6 pr-4">
            Lupa Kata Sandi? <a href="#" class="text-blue-600 hover:underline font-bold">Ubah Kata Sandi</a>
        </div>

        <div class="flex justify-between gap-x-3 mb-6">
            <x-form.button type="button" wireClick="authenticateStudent" label="Masuk Akun Siswa"
                class="flex-1 bg-[#4A90E2]" />
            <x-form.button type="button" wireClick="authenticateTeacher" label="Masuk Akun Guru"
                class="flex-1 bg-[#0651a7]" />
        </div>

        <div class="text-center text-sm text-gray-600">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold"
                wire:navigate>Daftar
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
