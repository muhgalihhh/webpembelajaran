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
                        <x-form.input-group label="Nama Lengkap" wire:model="name" id="name" type="text" />
                        <x-form.input-group label="Username" wire:model="username" id="username" type="text" />
                        <x-form.input-group label="Email" wire:model="email" id="email" type="email" disabled />
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
                        <x-form.input-group label="Password Baru" wire:model="password" id="password"
                            type="password" />
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
