<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>

        <h2 class="text-2xl font-bold text-gray-800">
            Manajemen Kelas
        </h2>

    </x-slot:pageHeader>
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <x-form.input-group label="Cari Kelas" name="search" wireModel="search"
                placeholder="Cari berdasarkan nama kelas atau ID grup WhatsApp" icon="fa-solid fa-search" />
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Kelas
                </x-form.button>

            </div>
        </div>
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md">


        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID
                            Grup WhatsApp</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->classes as $classItem)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $classItem->class }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $classItem->whatsapp_group_id ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="edit({{ $classItem->id }})"
                                    class="text-blue-500 hover:text-blue-700">Edit</button>
                                <button wire:click="confirmDelete({{ $classItem->id }})"
                                    class="ml-2 text-red-500 hover:text-red-700">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Tidak ada data kelas ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $this->classes->links() }}
        </div>
    </div>

    {{-- Modal Form Tambah/Edit Kelas --}}
    <x-ui.modal id="class-form-modal" :title="$isEditing ? 'Edit Kelas' : 'Tambah Kelas Baru'">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <x-form.input-group label="Nama Kelas" name="class" wireModel="class" placeholder="Contoh: 6A" />
                <x-form.input-group label="ID Grup WhatsApp" name="whatsapp_group_id" wireModel="whatsapp_group_id"
                    placeholder="Contoh: 12036304@g.us" />
                <x-form.textarea-group label="Deskripsi" name="description" wireModel="description" />
            </div>
            <div class="flex justify-end mt-6 space-x-2">
                <button type="button" x-on:click="$dispatch('close-modal')"
                    class="px-4 py-2 font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </x-ui.modal>
</div>
