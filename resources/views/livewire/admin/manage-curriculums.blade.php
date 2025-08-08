<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kurikulum</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Kurikulum" type="search" wireModel="search" id="search"
                    placeholder="Cari nama kurikulum..." />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Kurikulum
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop (Terlihat di layar lg ke atas) --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                        Kurikulum</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->curriculums as $curriculum)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $curriculum->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $curriculum->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $curriculum->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="edit({{ $curriculum->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $curriculum->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">Tidak ada data kurikulum ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $this->curriculums->links() }}
        </div>
    </div>

    {{-- Tampilan Kartu untuk Mobile (Tersembunyi di layar lg ke atas) --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($this->curriculums as $curriculum)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="text-lg font-bold text-gray-800">{{ $curriculum->name }}</div>
                    <span
                        class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $curriculum->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $curriculum->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="flex justify-end mt-4 space-x-4">
                    <button wire:click="edit({{ $curriculum->id }})"
                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button wire:click="confirmDelete({{ $curriculum->id }})"
                        class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">Tidak ada data kurikulum ditemukan.</div>
        @endforelse
        <div class="mt-4">
            {{ $this->curriculums->links() }}
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <x-ui.confirm-modal title="Hapus Kurikulum" message="Anda yakin ingin menghapus data kurikulum ini?"
        wireConfirmAction="delete" />

    {{-- Modal Form --}}
    <x-ui.modal id="curriculum-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Kurikulum</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="space-y-4">
                <x-form.input-group label="Nama Kurikulum" type="text" wireModel="name" id="name" required />
                <x-form.select-group label="Status" name="is_active" wireModel="is_active" :options="['1' => 'Aktif', '0' => 'Nonaktif']" required />
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>
</div>
