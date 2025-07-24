<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">Materi Pembelajaran</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                <input wire:model.live.debounce.300ms="search" id="search" type="search"
                    placeholder="Cari judul materi..."
                    class="w-full px-3 py-2 mt-1 bg-white border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="md:col-span-1">
                <label for="subjectFilter" class="block text-sm font-medium text-gray-700">Filter Mata Pelajaran</label>
                <select wire:model.live="subjectFilter" id="subjectFilter"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Mapel</option>
                    @foreach ($this->subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end justify-end md:col-span-1">
                <a href="{{ route('teacher.materials.create') }}" wire:navigate
                    class="px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Materi
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Data Materi --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('title')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Judul</th>
                    <th wire:click="sortBy('subject_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Mapel</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tipe</th>
                    <th wire:click="sortBy('is_published')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->materials as $material)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $material->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $material->subject->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($material->file_path)
                                <span class="text-blue-600"><i class="mr-1 fa-solid fa-file"></i> File</span>
                            @elseif($material->url)
                                <span class="text-green-600"><i class="mr-1 fa-solid fa-link"></i> Link</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $material->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.materials.edit', $material) }}" wire:navigate
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button wire:click="confirmDelete({{ $material->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Belum ada materi yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->materials->links() }}</div>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal :show="$confirmingDeletion" title="Hapus Materi"
        message="Anda yakin ingin menghapus materi ini? File yang terhubung juga akan dihapus."
        wireConfirmAction="delete" wireCancelAction="closeConfirmModal" />
</div>
