<x-ui.auth-card>
    <x-ui.auth-header>
        DAFTAR AKUN
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <div class="text-center mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:underline font-bold text-sm"
            wire:navigate>
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Halaman Utama
        </a>
    </div>

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form>
        @csrf

        <x-form.input-group type="text" id="full_name" placeholder="Masukkan Nama Lengkap" model="name"
            icon="fa-solid fa-user" required />
        <x-form.input-group type="text" id="reg_username" placeholder="Masukkan Username" model="username"
            icon="fa-solid fa-id-badge" required />
        <x-form.input-group type="email" id="reg_email" placeholder="Masukkan Email" model="email"
            {{-- type email --}} icon="fa-solid fa-envelope" required /> {{-- icon email --}}
        <x-form.input-group type="password" id="reg_password" placeholder="Masukkan Kata Sandi" model="password"
            icon="fa-solid fa-lock" passwordToggle required />
        <x-form.input-group type="password" id="reg_password_confirmation" placeholder="Konfirmasi Kata Sandi"
            model="password_confirmation" icon="fa-solid fa-key" passwordToggle required />

        <div class="flex justify-between gap-x-3 mb-6 mt-4">
            <x-form.button type="button" wire:click="registerStudent" label="Buat Akun Siswa"
                class="flex-1 bg-[#4A90E2] hover:bg-blue-700 text-lg" wire:loading.attr="disabled"
                wire:target="registerStudent">
                <span wire:loading.remove wire:target="registerStudent">Buat Akun Siswa</span>
                <span wire:loading wire:target="registerStudent">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>Mendaftar...
                </span>
            </x-form.button>

            <x-form.button type="button" wire:click="registerTeacher" label="Buat Akun Guru"
                class="flex-1 bg-[#0651a7] hover:bg-blue-800 text-lg" wire:loading.attr="disabled"
                wire:target="registerTeacher">
                <span wire:loading.remove wire:target="registerTeacher">Buat Akun Guru</span>
                <span wire:loading wire:target="registerTeacher">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>Mendaftar...
                </span>
            </x-form.button>
        </div>

        <div class="text-center text-sm text-gray-600 mt-6">
            Sudah Punya Akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold"
                wire:navigate>Masuk
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
