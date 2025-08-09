<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;">
    <x-ui.student.container title="Profil Saya" icon="fa-solid fa-user-circle">

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- Kolom Kiri: Form Edit Profil & Ganti Password --}}
            <div class="lg:col-span-2">
                {{-- Card Edit Profil --}}
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="pb-4 text-xl font-bold text-gray-800 border-b">Informasi Profil</h3>
                    <form wire:submit.prevent="updateUser" class="mt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                            <div class="flex items-center mt-2 gap-x-3">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                        class="object-cover w-20 h-20 rounded-full">
                                @elseif (Auth::user()->profile_picture)
                                    <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="Foto Profil"
                                        class="object-cover w-20 h-20 rounded-full">
                                @else
                                    <svg class="w-20 h-20 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                                <input type="file" wireModel="photo" id="photo" class="hidden">
                                <label for="photo"
                                    class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Ganti Foto
                                </label>
                                <div wire:loading wire:target="photo" class="text-sm text-gray-500">Uploading...</div>
                            </div>
                            @error('photo')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-form.input-group label="Nama Lengkap" wireModel="name" id="name" type="text" />
                        <x-form.input-group label="Username" wireModel="username" id="username" type="text" />
                        <x-form.input-group label="Email" wireModel="email" id="email" type="email" disabled />
                        <div class="flex justify-end">
                            <x-form.button type="submit" variant="primary">
                                <span wire:loading.remove wire:target="updateUser">Simpan Perubahan</span>
                                <span wire:loading wire:target="updateUser">Menyimpan...</span>
                            </x-form.button>
                        </div>
                    </form>
                </div>

                {{-- Card Ganti Password --}}
                <div class="p-6 mt-8 bg-white rounded-lg shadow-md">
                    <h3 class="pb-4 text-xl font-bold text-gray-800 border-b">Ubah Password</h3>
                    <form wire:submit.prevent="changePassword" class="mt-6 space-y-4">
                        <x-form.input-group label="Password Baru" wireModel="password" id="password" type="password" />
                        <x-form.input-group label="Konfirmasi Password Baru" wireModel="password_confirmation"
                            id="password_confirmation" type="password" />
                        <div class="flex justify-end">
                            <x-form.button type="submit" variant="primary">
                                <span wire:loading.remove wire:target="changePassword">Ubah Password</span>
                                <span wire:loading wire:target="changePassword">Menyimpan...</span>
                            </x-form.button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Info Kelas --}}
            <div class="lg:col-span-1">
                <div class="p-6 text-center bg-white rounded-lg shadow-md">
                    <h3 class="pb-4 mb-6 text-xl font-bold text-gray-800 border-b">Informasi Kelas</h3>
                    @if ($this->classInfo)
                        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full">
                            <i class="text-4xl text-blue-600 fa-solid fa-school"></i>
                        </div>
                        <p class="text-gray-600">Anda terdaftar di kelas:</p>
                        <p class="text-3xl font-bold text-blue-600">Kelas {{ $this->classInfo->class }}</p>

                        @if ($this->classInfo->whatsapp_group_link)
                            <a href="{{ $this->classInfo->whatsapp_group_link }}" target="_blank"
                                class="inline-flex items-center justify-center w-full px-4 py-3 mt-6 font-bold text-white transition bg-green-500 rounded-lg shadow-lg hover:bg-green-600">
                                <i class="mr-2 fab fa-whatsapp"></i>
                                Gabung Grup WhatsApp
                            </a>
                        @else
                            <div class="px-4 py-3 mt-6 text-sm text-center text-gray-700 bg-gray-100 rounded-lg">
                                Link grup WhatsApp belum tersedia.
                            </div>
                        @endif
                    @else
                        <p>Anda belum terdaftar di kelas manapun.</p>
                    @endif
                </div>
            </div>
        </div>

    </x-ui.student.container>
</div>
