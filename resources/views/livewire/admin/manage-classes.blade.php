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
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Kelas" type="search" wireModel="search" id="search"
                    placeholder="Cari tingkatan kelas..." />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Kelas
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Kelas --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('class')"
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
                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->class }}</td>
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
            <x-form.select-group label="Tingkat Kelas" name="class" wireModel="class" :options="['I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI']" required />
            <x-form.textarea-group label="Deskripsi (Opsional)" name="description" wireModel="description" />
            <div class="flex justify-end pt-4 space-x-4">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Kelas" message="Anda yakin ingin menghapus data kelas ini?"
        wireConfirmAction="delete" />
</div>
