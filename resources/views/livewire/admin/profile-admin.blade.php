<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
    </x-slot:pageHeader>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="pb-3 text-lg font-medium text-gray-900 border-b">Informasi Profil</h3>
                <form wire:submit.prevent="updateProfile" class="mt-6 space-y-4">

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
                            <input type="file" wire:model="photo" id="photo" class="hidden">
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

                    <x-form.input-group label="Nama Lengkap" type="text" wireModel="name" id="name" required />
                    <x-form.input-group label="Username" type="text" wireModel="username" id="username" required />
                    <x-form.input-group label="Email" type="email" wireModel="email" id="email" required />
                    <x-form.input-group label="No. Telepon" type="tel" wireModel="phone_number" id="phone_number" />

                    <div class="flex justify-end">
                        <x-form.button type="submit" variant="primary">
                            Simpan Perubahan
                        </x-form.button>
                    </div>
                </form>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="pb-3 text-lg font-medium text-gray-900 border-b">Ubah Kata Sandi</h3>
                <form wire:submit.prevent="updatePassword" class="mt-6 space-y-4">
                    <x-form.input-group label="Kata Sandi Saat Ini" type="password" wireModel="current_password"
                        id="current_password" required passwordToggle />
                    <x-form.input-group label="Kata Sandi Baru" type="password" wireModel="password" id="password"
                        required passwordToggle />
                    <x-form.input-group label="Konfirmasi Kata Sandi Baru" type="password"
                        wireModel="password_confirmation" id="password_confirmation" required passwordToggle />

                    <div class="flex justify-end">
                        <x-form.button type="submit" variant="primary">
                            Ubah Kata Sandi
                        </x-form.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
