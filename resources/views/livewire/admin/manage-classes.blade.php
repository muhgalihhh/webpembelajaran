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
            <div class="flex-1">
                <x-form.input-group label="Cari Kelas" name="search" wireModel="search"
                    placeholder="Cari berdasarkan nama kelas atau ID grup WhatsApp" icon="fa-solid fa-search" />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Kelas
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop --}}
    <div class="hidden bg-white rounded-lg shadow-md lg:block">
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
                        {{-- Kolom BARU --}}
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Link
                            Grup</th>
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
                            {{-- Data BARU --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($classItem->whatsapp_group_link)
                                    <a href="{{ $classItem->whatsapp_group_link }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        Lihat Link
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="edit({{ $classItem->id }})"
                                    class="text-blue-500 hover:text-blue-700">Edit</button>
                                <button wire:click="confirmDelete({{ $classItem->id }})"
                                    class="ml-2 text-red-500 hover:text-red-700">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Sesuaikan colspan --}}
                            <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data kelas ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $this->classes->links() }}
        </div>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($this->classes as $classItem)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="text-lg font-bold text-gray-800">{{ $classItem->class }}</div>
                <div class="mt-1 text-sm text-gray-600">
                    <p><span class="font-semibold">ID Grup WA:</span> {{ $classItem->whatsapp_group_id ?? '-' }}</p>
                    {{-- Info BARU --}}
                    <p><span class="font-semibold">Link Grup:</span>
                        @if ($classItem->whatsapp_group_link)
                            <a href="{{ $classItem->whatsapp_group_link }}" target="_blank"
                                class="text-blue-600 hover:underline">
                                Lihat Link
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="flex justify-end mt-4 space-x-4">
                    <button wire:click="edit({{ $classItem->id }})"
                        class="text-blue-500 hover:text-blue-700">Edit</button>
                    <button wire:click="confirmDelete({{ $classItem->id }})"
                        class="text-red-500 hover:text-red-700">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">Tidak ada data kelas ditemukan.</div>
        @endforelse
        <div class="mt-4">
            {{ $this->classes->links() }}
        </div>
    </div>

    <x-ui.modal id="class-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit Kelas' : 'Tambah Kelas Baru' }}</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="space-y-4">
                <x-form.input-group label="Nama Kelas" name="class" wireModel="class" placeholder="Contoh: 6A" />
                <x-form.input-group label="ID Grup WhatsApp" name="whatsapp_group_id" wireModel="whatsapp_group_id"
                    placeholder="Contoh: 12036304@g.us" />

                <x-form.input-group label="Link Grup WhatsApp (Opsional)" name="whatsapp_group_link"
                    wireModel="whatsapp_group_link" placeholder="Contoh: https://chat.whatsapp.com/..."
                    type="url" />

                <x-form.textarea-group label="Deskripsi (Opsional)" name="description" wireModel="description" />
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-2 border-t">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    <x-ui.confirm-modal title="Hapus Kelas" message="Anda yakin ingin menghapus data kelas ini?"
        wireConfirmAction="delete" />
</div>
