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

    {{-- Form Registrasi dengan container yang diperbaiki --}}
    <div class="relative overflow-visible">
        <form wire:submit.prevent="register">
            @csrf

            {{-- Tata letak formulir yang diperbarui --}}
            <div class="space-y-4">
                {{-- Baris 1: Nama dan Username --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-form.input-group type="text" id="full_name" placeholder="Masukkan Nama Lengkap" wireModel="name"
                        icon="fa-solid fa-user" required />
                    <x-form.input-group type="text" id="reg_username" placeholder="Masukkan Username"
                        wireModel="username" icon="fa-solid fa-id-badge" required />
                </div>

                {{-- Baris 2: Email dan Gender --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-form.input-group type="email" id="reg_email" placeholder="Masukkan Email" wireModel="email"
                        icon="fa-solid fa-envelope" required />

                    {{-- Gender Field dengan styling yang disesuaikan --}}
                    <div class="relative">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fa-solid fa-venus-mars text-gray-400"></i>
                            </div>
                            <select id="gender" wire:model="gender"
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white text-gray-900 appearance-none"
                                required>
                                <option value="" class="text-gray-500">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            {{-- Custom dropdown arrow --}}
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Baris 3: Nomor Telepon full-width --}}
                <x-form.input-group type="tel" id="phone_number" placeholder="Masukkan Nomor Telepon (cth: 0812...)"
                    wireModel="phone_number" icon="fa-solid fa-phone" required />

                {{-- Baris 4: Password --}}
                <x-form.input-group type="password" id="reg_password" placeholder="Masukkan Kata Sandi" wireModel="password"
                    icon="fa-solid fa-lock" passwordToggle required />

                {{-- Baris 5: Konfirmasi Password --}}
                <x-form.input-group type="password" id="reg_password_confirmation" placeholder="Konfirmasi Kata Sandi"
                    wireModel="password_confirmation" icon="fa-solid fa-key" passwordToggle required />

                {{-- Kolom khusus untuk Siswa dengan container dropdown yang diperbaiki --}}
                @if ($activeTab === 'siswa')
                    <div wire:key="student-fields" class="relative z-10">
                        {{-- Tambahan margin bottom untuk memberi ruang dropdown --}}
                        <div class="pb-32 mb-4">
                            <x-form.select-group
                                label="Pilih Kelas Anda"
                                name="class_id"
                                wireModel="class_id"
                                :options="$this->classes"
                                wire:model.live='class_id'
                                optionLabel="class"
                                required
                                class="dropdown-container" />
                        </div>
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
    </div>

    {{-- CSS tambahan untuk mengatasi masalah dropdown --}}
    <style>
        /* Pastikan dropdown tidak terpotong */
        .dropdown-container select {
            position: relative;
            z-index: 50;
        }

        /* Untuk custom dropdown/select yang mungkin menggunakan JavaScript */
        .dropdown-menu,
        .select-dropdown {
            position: absolute !important;
            z-index: 9999 !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            max-height: 200px !important;
            overflow-y: auto !important;
            background: white !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        /* Pastikan container form tidak memotong dropdown */
        .auth-card,
        .form-container {
            overflow: visible !important;
        }

        /* Styling untuk option dropdown */
        .dropdown-menu option,
        .select-dropdown .option {
            padding: 8px 12px !important;
            cursor: pointer !important;
        }

        .dropdown-menu option:hover,
        .select-dropdown .option:hover {
            background-color: #f3f4f6 !important;
        }

        /* Additional styling for gender select */
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Custom select styling to match input fields */
        select {
            background-image: none;
        }

        /* Consistent height and padding for all form elements */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        select {
            height: 48px;
            font-size: 16px;
        }

        /* Better spacing for form rows */
        .space-y-4 > * + * {
            margin-top: 1rem;
        }
    </style>
</x-ui.auth-card>
