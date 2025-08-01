<x-ui.auth-card>
    <x-ui.auth-header>
        DAFTAR AKUN
        <div class="mt-1 text-sm font-normal">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <div class="mb-4 text-center">
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline"
            wire:navigate>
            <i class="mr-2 fa-solid fa-arrow-left"></i>
            Kembali ke Halaman Utama
        </a>
    </div>

    @if (session()->has('error'))
        <div class="relative px-4 py-3 mb-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Navigasi Tab --}}
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
            <li class="mr-2" role="presentation">
                <button
                    class="inline-block px-4 py-3 border-b-2 rounded-t-lg transition-colors duration-300 {{ $activeTab === 'siswa' ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                    wire:click="setTab('siswa')" type="button" role="tab">
                    Daftar sebagai Siswa
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button
                    class="inline-block px-4 py-3 border-b-2 rounded-t-lg transition-colors duration-300 {{ $activeTab === 'guru' ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                    wire:click="setTab('guru')" type="button" role="tab">
                    Daftar sebagai Guru
                </button>
            </li>
        </ul>
    </div>

    {{-- Form Registrasi --}}
    <form wire:submit.prevent="register">
        @csrf

        {{-- Tata letak formulir yang diperbarui --}}
        <div class="space-y-3">
            {{-- Grid hanya untuk Nama dan Username --}}
            <div class="grid grid-cols-1 gap-x-4 md:grid-cols-2">
                <x-form.input-group type="text" id="full_name" placeholder="Masukkan Nama Lengkap" wireModel="name"
                    icon="fa-solid fa-user" required />
                <x-form.input-group type="text" id="reg_username" placeholder="Masukkan Username"
                    wireModel="username" icon="fa-solid fa-id-badge" required />
            </div>

            {{-- Kolom lainnya full-width --}}
            <x-form.input-group type="email" id="reg_email" placeholder="Masukkan Email" wireModel="email"
                icon="fa-solid fa-envelope" required />
            <x-form.input-group type="tel" id="phone_number" placeholder="Masukkan Nomor Telepon (cth: 0812...)"
                wireModel="phone_number" icon="fa-solid fa-phone" required />
            <x-form.input-group type="password" id="reg_password" placeholder="Masukkan Kata Sandi" wireModel="password"
                icon="fa-solid fa-lock" passwordToggle required />
            <x-form.input-group type="password" id="reg_password_confirmation" placeholder="Konfirmasi Kata Sandi"
                wireModel="password_confirmation" icon="fa-solid fa-key" passwordToggle required />

            {{-- Kolom khusus untuk Siswa (tetap full-width) --}}
            @if ($activeTab === 'siswa')
                <div wire:key="student-fields">
                    <x-form.select-group label="Pilih Kelas Anda" name="class_id" wireModel="class_id" :options="$this->classes"
                        optionLabel="class" {{-- Sesuaikan dengan nama kolom di tabel 'classes' --}} required />
                </div>
            @endif
        </div>

        {{-- Tombol Submit Dinamis --}}
        <div class="mt-4">
            <x-form.button type="submit" class="w-full bg-[#4A90E2] hover:bg-blue-700 text-lg"
                wire:loading.attr="disabled" wire:target="register">
                <span wire:loading.remove wire:target="register">
                    Buat Akun {{ $activeTab === 'siswa' ? 'Siswa' : 'Guru' }}
                </span>
                <span wire:loading wire:target="register">
                    <i class="mr-2 fa-solid fa-spinner fa-spin"></i>Mendaftar...
                </span>
            </x-form.button>
        </div>

        <div class="mt-4 text-sm text-center text-gray-600">
            Sudah Punya Akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline"
                wire:navigate>Masuk
                Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
