<div>
    <x-slot:pageHeader>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Materi Pembelajaran</h2>
            <a href="{{ route('teacher.materials.create') }}" wire:navigate
                class="flex items-center justify-center w-full px-4 py-2 mt-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm sm:w-auto sm:mt-0 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="mr-2 fa-solid fa-plus"></i>
                Tambah Materi Baru
            </a>
        </div>
    </x-slot:pageHeader>

    {{-- Filter Container --}}
    <div class="flex flex-col gap-4 mt-4 md:flex-row md:items-end">

        {{-- Filter 1: Mata Pelajaran --}}
        <div class="flex-1">
            <x-form.select-group label="Mata Pelajaran" id="filterSubject" wireModel="filterSubject" :options="$this->subjects"
                name="mata_pelajaran" />
        </div>

        {{-- Filter 3: (Contoh lain) --}}
        <div class="flex-1">
            <x-form.select-group label="Status" id="filterStatus" wireModel="filterStatus" :options="['published' => 'Published', 'draft' => 'Draft']"
                name="status" />
        </div>

        {{-- Input Pencarian --}}
        <div class="flex-1">
            <x-form.input-group type="text" id="search" wireModel="search" placeholder="Cari Judul Materi..."
                label="Pencarian" icon="fa-solid fa-magnifying-glass" />
        </div>

    </div>

    {{-- CARD 2: UNTUK TABEL DATA --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" wire:click="sortBy('title')"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                            Judul Materi
                            @if ($sortBy === 'title')
                                <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th scope="col" wire:click="sortBy('subject_id')"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                            Mata Pelajaran
                            @if ($sortBy === 'subject_id')
                                <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th scope="col" wire:click="sortBy('chapter')"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                            BAB
                            @if ($sortBy === 'chapter')
                                <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Pembuat
                        </th>
                        <th scope="col" wire:click="sortBy('is_published')"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                            Status
                            @if ($sortBy === 'is_published')
                                <i class="fa-solid fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            File
                        </th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->materials as $material)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $material->title }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $material->subject?->name ?? 'Mapel Dihapus' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $material->chapter ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $material->uploader?->name ?? 'User Dihapus' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($material->is_published)
                                    <span
                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Published</span>
                                @else
                                    <span
                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2 text-sm">
                                    @if ($material->file_path)
                                        <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                                            class="text-blue-500 hover:underline">
                                            <i class="fa-solid fa-file"></i>
                                            {{ $material->file_name }}
                                        </a>
                                        <button wire:click="download('{{ $material->id }}')"
                                            class="text-blue-500 hover:underline">
                                            <i class="fa-solid fa-download"></i>
                                        </button>
                                    @elseif($material->youtube_url)
                                        <a href="{{ $material->youtube_url }}" target="_blank"
                                            class="text-blue-500 hover:underline">Lihat Video</a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <a href="{{ route('teacher.materials.edit', $material) }}" wire:navigate
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>


                                <button wire:click="confirmDelete({{ $material->id }})"
                                    class="ml-4 text-red-600 hover:text-red-900">
                                    Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-500">
                                Tidak ada materi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="p-4">
            {{ $this->materials->links() }}
        </div>
    </div>
    <x-ui.confirm-modal :show="$confirmingDeletion" title="Hapus Materi Pembelajaran"
        message="Anda yakin ingin menghapus materi pembelajaran ini? File yang terlampir juga akan ikut terhapus."
        wireConfirmAction="delete" wireCancelAction="closeConfirmModal" />
</div>
