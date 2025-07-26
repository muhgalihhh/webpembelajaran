<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Mata Pelajaran</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Mapel" type="search" wireModel="search" id="search"
                    placeholder="Cari nama mapel..." />
            </div>
            <div class="flex-1">
                <x-form.select-group label="Filter Status" name="status_filter" wireModel="status_filter"
                    :options="['1' => 'Aktif', '0' => 'Nonaktif']" />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Mapel
                </x-form.button>
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
    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Mata Pelajaran" message="Anda yakin ingin menghapus data mata pelajaran ini?"
        wireConfirmAction="delete" />
    {{-- Modal Form --}}
    <x-ui.modal id="subject-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Mata Pelajaran</h2>
        <form wire:submit.prevent="save" class="mt-4 space-y-4">
            <x-form.input-group label="Nama Mata Pelajaran" type="text" wireModel="name" id="name" required />
            <x-form.input-group label="Kode Mata Pelajaran" type="text" wireModel="code" id="code" required />
            <x-form.select-group label="Status" name="is_active" wireModel="is_active" :options="['1' => 'Aktif', '0' => 'Nonaktif']" required />

            <div class="flex justify-end pt-4 space-x-4">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>



</div>
