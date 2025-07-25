<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Mata Pelajaran</h2>
    </x-slot:pageHeader>
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                <input wire:model.live.debounce.300ms="search" id="search" type="search"
                    placeholder="Cari nama mapel..."
                    class="w-full px-3 py-2 mt-1 bg-white border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="md:col-span-1">
                <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter Status</label>
                <div class="relative mt-1">
                    <select wire:model.live="status_filter" id="status_filter"
                        class="block w-full px-3 py-2 pr-10 text-base bg-white border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 pointer-events-none">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.28a.75.75 0 011.06 0L10 15.19l2.67-2.91a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-end md:col-span-1">
                <button wire:click="create"
                    class="px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Mapel
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Mapel --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama Mapel</th>
                    <th wire:click="sortBy('code')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Kode</th>
                    <th wire:click="sortBy('is_active')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->subjects as $subject)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $subject->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $subject->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subject->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="edit({{ $subject->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $subject->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data mata pelajaran
                            ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->subjects->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="subject-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Mata Pelajaran</h2>
        <form wire:submit.prevent="save" class="mt-4 space-y-4">
            <div>
                <label for="name">Nama Mata Pelajaran</label>
                <input type="text" id="name" wire:model="name" class="w-full p-2 border rounded">
                @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="code">Kode Mata Pelajaran</label>
                <input type="text" id="code" wire:model="code" class="w-full p-2 border rounded">
                @error('code')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="is_active" wire:model="is_active" class="w-full p-2 mt-1 border rounded">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
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
    <x-ui.confirm-modal :show="$confirmingDeletion" title="Hapus Mata Pelajaran"
        message="Anda yakin ingin menghapus data mata pelajaran ini?" wireConfirmAction="delete"
        wireCancelAction="closeConfirmModal" />
</div>
