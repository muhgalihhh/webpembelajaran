<div>
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Materi Pembelajaran</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-form.input-group label="Pencarian Materi" type="search" wireModel="search" id="search"
                placeholder="Cari judul materi..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="filterSubject" wireModel="filterSubject"
                :options="$this->subjects" />
            <x-form.select-group label="Filter Kelas" name="filterClass" wireModel="filterClass" :options="$this->classes"
                wire:model.live='filterClass' optionLabel="class" />
            <x-form.select-group label="Filter Status" name="filterPublished" wireModel="filterPublished"
                wire:model.live='filterPublished' :options="['' => 'Semua Status', '1' => 'Published', '0' => 'Draft']" />
            <div class="lg:col-start-4">
                <a href="{{ route('teacher.materials.create') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
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
                        Judul
                        @if ($sortBy === 'title')
                            <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('subject_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Mapel
                        @if ($sortBy === 'subject_id')
                            <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('class_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Kelas
                        @if ($sortBy === 'class_id')
                            <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('is_published')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status
                        @if ($sortBy === 'is_published')
                            <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->materials as $material)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $material->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $material->subject?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $material->class?->class ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $material->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="view({{ $material->id }})"
                                class="text-green-600 hover:text-green-900">Lihat</button>
                            <a href="{{ route('teacher.materials.edit', $material) }}" wire:navigate
                                class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button wire:click="confirmDelete({{ $material->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                            @if ($material->file_path)
                                <a href="{{ route('materials.download', $material) }}" ...
                                    class="ml-4 text-indigo-600 hover:text-indigo-900">
                                    <i class="mr-2 fas fa-download"></i> Unduh Materi
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada materi ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->materials->links() }}</div>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Materi"
        message="Anda yakin ingin menghapus materi ini? File yang terlampir juga akan dihapus."
        wireConfirmAction="delete" />

    {{-- Pemanggilan komponen modal preview --}}
    <x-ui.teacher.material-preview-modal :title="$viewingMaterial?->title" :description="$viewingMaterial?->description" :content="$viewingMaterial?->content" :subject="$viewingMaterial?->subject?->name"
        :class="$viewingMaterial?->class?->class" />
</div>
