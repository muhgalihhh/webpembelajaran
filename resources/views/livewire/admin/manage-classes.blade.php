<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kelas</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col justify-between space-y-4 md:flex-row md:space-y-0">
            <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                <input wire:model.live.debounce.300ms="search" id="search" type="search"
                    placeholder="Cari nama kelas..."
                    class="w-full px-3 py-2 mt-1 bg-white border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="flex items-end">
                <button wire:click="create"
                    class="px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Kelas --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama Kelas</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Deskripsi
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->classes as $class)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="edit({{ $class->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $class->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">Tidak ada data kelas ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->classes->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="class-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Kelas</h2>
        <form wire:submit.prevent="save" class="mt-4 space-y-4">
            <div>
                <label for="name">Nama Kelas</label>
                <input type="text" id="name" wire:model="name" class="w-full p-2 border rounded">
                @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="description">Deskripsi (Opsional)</label>
                <textarea id="description" wire:model="description" class="w-full p-2 border rounded" rows="3"></textarea>
                @error('description')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end pt-4 space-x-4">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal :show="$confirmingDeletion" title="Hapus Kelas" message="Anda yakin ingin menghapus data kelas ini?"
        wireConfirmAction="delete" wireCancelAction="closeConfirmModal" />
</div>
