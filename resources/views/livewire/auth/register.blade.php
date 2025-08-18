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

    {{-- Form Registrasi hanya untuk Siswa --}}
    <div class="relative overflow-visible">
        <form wire:submit.prevent="register">
            @csrf

            <div class="space-y-4">
                {{-- Baris 1: Nama dan Username --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-form.input-group type="text" id="full_name" placeholder="Masukkan Nama Lengkap"
                        wireModel="name" icon="fa-solid fa-user" required />
                    <x-form.input-group type="text" id="reg_username" placeholder="Masukkan Username"
                        wireModel="username" icon="fa-solid fa-id-badge" required />
                </div>

                {{-- Baris 2: Email dan Gender --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-form.input-group type="email" id="reg_email" placeholder="Masukkan Email" wireModel="email"
                        icon="fa-solid fa-envelope" required />

                    <x-form.select-group label="Pilih Jenis Kelamin" name="gender" wireModel="gender" :options="[['id' => 'L', 'class' => 'Laki-laki'], ['id' => 'P', 'class' => 'Perempuan']]"
                        optionLabel="class" optionValue="id" required />
                </div>

                {{-- Baris 3: Nomor Telepon --}}
                <x-form.input-group type="tel" id="phone_number" placeholder="Masukkan Nomor Telepon (cth: 0812...)"
                    wireModel="phone_number" icon="fa-solid fa-phone" required />

                {{-- Baris 4: Password --}}
                <x-form.input-group type="password" id="reg_password" placeholder="Masukkan Kata Sandi"
                    wireModel="password" icon="fa-solid fa-lock" passwordToggle required />

                {{-- Baris 5: Konfirmasi Password --}}
                <x-form.input-group type="password" id="reg_password_confirmation" placeholder="Konfirmasi Kata Sandi"
                    wireModel="password_confirmation" icon="fa-solid fa-key" passwordToggle required />

                {{-- Kolom khusus untuk Siswa --}}
                <div wire:key="student-fields" class="relative z-10">
                    <div class="pb-32 mb-4">
                        <x-form.select-group label="Pilih Kelas Anda" name="class_id" wireModel="class_id"
                            :options="$this->classes" wire:model.live='class_id' optionLabel="class" required
                            class="dropdown-container" />
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-4">
                <x-form.button type="submit" class="w-full bg-[#4A90E2] hover:bg-blue-700 text-lg"
                    wire:loading.attr="disabled" wire:target="register">
                    <span wire:loading.remove wire:target="register">
                        Buat Akun Siswa
                    </span>
                    <span wire:loading wire:target="register">
                        <i class="mr-2 fa-solid fa-spinner fa-spin"></i>Mendaftar...
                    </span>
                </x-form.button>
            </div>

            <div class="mt-4 text-sm text-center text-gray-600">
                Sudah Punya Akun?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline" wire:navigate>Masuk
                    Sekarang!</a>
            </div>
        </form>
    </div>

    {{-- CSS tambahan --}}
    <style>
        .dropdown-container select {
            position: relative;
            z-index: 50;
        }

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
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        .auth-card,
        .form-container {
            overflow: visible !important;
        }

        .dropdown-menu option,
        .select-dropdown .option {
            padding: 8px 12px !important;
            cursor: pointer !important;
        }

        .dropdown-menu option:hover,
        .select-dropdown .option:hover {
            background-color: #f3f4f6 !important;
        }

        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        select {
            background-image: none;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        select {
            height: 48px;
            font-size: 16px;
        }

        /* Better spacing for form rows */
        .space-y-4>*+* {
            margin-top: 1rem;
        }
    </style>
</x-ui.auth-card>
