<div>
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Game Edukasi</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Game" type="search" wireModel="search" id="search"
                    placeholder="Cari judul game..." />
            </div>
            <div class="flex-1">
                <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                    :options="$this->subjects" wire:model.live='subjectFilter' optionLabel="name" />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Game
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Daftar Game dalam Grid --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($this->games as $game)
            <div class="flex flex-col overflow-hidden bg-white rounded-lg shadow-md">
                <img src="{{ $game->image_path ? Storage::url($game->image_path) : 'https://placehold.co/600x400/e2e8f0/e2e8f0' }}"
                    alt="{{ $game->title }}" class="object-cover w-full h-48">
                <div class="flex flex-col flex-grow p-4">
                    <span
                        class="text-xs font-semibold text-blue-600 uppercase">{{ $game->subject->name ?? 'N/A' }}</span>
                    <h3 class="mt-1 text-lg font-bold text-gray-800">{{ $game->title }}</h3>
                    <p class="flex-grow mt-2 text-sm text-gray-600">{{ Str::limit($game->description, 100) }}</p>
                    <div class="flex items-center justify-between pt-4 mt-4 border-t">
                        <a href="{{ $game->game_url }}" target="_blank"
                            class="text-sm text-blue-500 hover:underline">Mainkan Game &rarr;</a>
                        <div>
                            <button wire:click="edit({{ $game->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $game->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow-md lg:col-span-3">
                Tidak ada game edukasi ditemukan.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $this->games->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="game-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Game Edukasi</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-form.input-group label="Judul Game" type="text" wireModel="title" id="title" required />
                </div>
                <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id" :options="$this->subjects"
                    required />
                <x-form.input-group label="URL Game" type="url" wireModel="game_url" id="game_url"
                    placeholder="https://..." required />
                <div class="md:col-span-2">
                    <x-form.textarea-group label="Deskripsi" name="description" wireModel="description" required />
                </div>
                <div class="md:col-span-2">
                    <label for="uploadedImage" class="block text-sm font-medium text-gray-700">Gambar Sampul</label>
                    <input type="file" id="uploadedImage" wire:model="uploadedImage"
                        class="w-full mt-1 file-input file-input-bordered">
                    <div wire:loading wire:target="uploadedImage" class="mt-1 text-xs text-gray-500">Uploading...</div>
                    @if ($uploadedImage)
                        <img src="{{ $uploadedImage->temporaryUrl() }}" class="h-32 mt-2 rounded-md">
                    @elseif($currentImagePath)
                        <img src="{{ Storage::url($currentImagePath) }}" class="h-32 mt-2 rounded-md">
                    @endif
                    @error('uploadedImage')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Game" message="Anda yakin ingin menghapus game edukasi ini?"
        wireConfirmAction="delete" />
</div>
