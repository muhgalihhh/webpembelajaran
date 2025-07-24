<div>
    <x-slot:pageHeader>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Materi Pembelajaran</h2>
            <a href="{{ route('teacher.materials.create') }}" wire:navigate
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="mr-2 fa-solid fa-plus"></i>
                Tambah Materi Baru
            </a>
        </div>
    </x-slot:pageHeader>

    {{-- CARD 1: UNTUK FILTER --}}
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Filter Pencarian</h3>
        <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-3">
            {{-- Filter Mata Pelajaran --}}
            <div>
                <label for="filterSubject" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                <select id="filterSubject" wire:model.live="filterSubject"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach ($this->subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label for="filterPublished" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="filterPublished" wire:model.live="filterPublished"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="1">Published</option>
                    <option value="0">Draft</option>
                </select>
            </div>

            {{-- Input Pencarian Judul --}}
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Judul Materi</label>
                <input id="search" type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul..."
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    {{-- CARD 2: UNTUK TABEL DATA --}}
    <div class="mt-6 bg-white rounded-lg shadow-md">

        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Judul Materi</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Mata Pelajaran</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            BAB</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Pembuat</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            File</th>
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
                            <td class="px-6 py-4 text-sm text-blue-500 whitespace-nowrap">
                                @if ($material->file_path)
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                                        class="hover:underline">Lihat File</a>
                                @elseif($material->youtube_url)
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="hover:underline">Lihat
                                        Video</a>
                                @else
                                    -
                                @endif
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
        <div class="mt-4">
            {{ $this->materials->links() }}
        </div>
    </div>
    <x-ui.confirm-modal :show="$confirmingDeletion" title="Hapus Materi Pembelajaran"
        message="Anda yakin ingin menghapus materi pembelajaran ini? File yang terlampir juga akan ikut terhapus."
        wireConfirmAction="delete" wireCancelAction="closeConfirmModal" />
</div>
