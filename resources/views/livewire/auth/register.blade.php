<x-ui.auth-card>
    <x-ui.auth-header>
        DAFTAR AKUN
        <div class="mt-1 text-sm font-normal">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <div class="mb-6 text-center">
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline"
            wire:navigate>
            <i class="mr-2 fa-solid fa-arrow-left"></i>
            Kembali ke Halaman Utama
        </a>
    </div>

    @if (session()->has('error'))
        <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form>
        @csrf

        <x-form.input-group type="text" id="full_name" placeholder="Masukkan Nama Lengkap" wireModel="name"
            icon="fa-solid fa-user" required />
        <x-form.input-group type="text" id="reg_username" placeholder="Masukkan Username" wireModel="username"
            icon="fa-solid fa-id-badge" required />
        <x-form.input-group type="email" id="reg_email" placeholder="Masukkan Email" wireModel="email"
            {{-- type email --}} icon="fa-solid fa-envelope" required /> {{-- icon email --}}
        <x-form.input-group type="password" id="reg_password" placeholder="Masukkan Kata Sandi" wireModel="password"
            icon="fa-solid fa-lock" passwordToggle required />
        <x-form.input-group type="password" id="reg_password_confirmation" placeholder="Konfirmasi Kata Sandi"
            wireModel="password_confirmation" icon="fa-solid fa-key" passwordToggle required />

        <div class="flex justify-between mt-4 mb-6 gap-x-3">
            <x-form.button type="button" wire:click="registerStudent" label="Buat Akun Siswa"
                class="flex-1 bg-[#4A90E2] hover:bg-blue-700 text-lg" wire:loading.attr="disabled"
                wire:target="registerStudent">
                <span wire:loading.remove wire:target="registerStudent">Buat Akun Siswa</span>
                <span wire:loading wire:target="registerStudent">
                    <i class="mr-2 fa-solid fa-spinner fa-spin"></i>Mendaftar...
                </span>
            </x-form.button>

            <x-form.button type="button" wire:click="registerTeacher" label="Buat Akun Guru"
                class="flex-1 bg-[#0651a7] hover:bg-blue-800 text-lg" wire:loading.attr="disabled"
                wire:target="registerTeacher">
                <span wire:loading.remove wire:target="registerTeacher">Buat Akun Guru</span>
                <span wire:loading wire:target="registerTeacher">
                    <i class="mr-2 fa-solid fa-spinner fa-spin"></i>Mendaftar...
                </span>
            </x-form.button>
        </div>

        <div class="mt-6 text-sm text-center text-gray-600">
            Sudah Punya Akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline"
                wire:navigate>Masuk
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
