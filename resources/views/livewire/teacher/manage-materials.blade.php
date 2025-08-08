<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Materi Pembelajaran</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
            <div class="lg:col-span-1">
                <x-form.input-group label="Pencarian Materi" type="search" wireModel="search" id="search"
                    placeholder="Cari judul materi..." />
            </div>
            <div class="lg:col-span-1">
                <x-form.select-group label="Filter Mata Pelajaran" name="filterSubject" wireModel="filterSubject"
                    :options="$this->subjects" optionLabel="name" placeholder="Semua Mapel" />
            </div>
            <div class="lg:col-span-1">
                <x-form.select-group label="Filter Kelas" name="filterClass" wireModel="filterClass" :options="$this->classes"
                    optionLabel="class" placeholder="Semua Kelas" />
            </div>
            <div class="lg:col-span-1">
                <x-form.select-group label="Filter Status" name="filterPublished" wireModel="filterPublished"
                    :options="['' => 'Semua Status', '1' => 'Published', '0' => 'Draft']" />
            </div>
            <div class="lg:col-span-1">
                <a href="{{ route('teacher.materials.create') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Materi
                </a>
            </div>
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
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
                                class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $material->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
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

    <div class="grid grid-cols-1 gap-4 mt-6 lg:hidden">
        @forelse ($this->materials as $material)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $material->title }}</h3>
                        <p class="text-sm text-gray-500">{{ Str::limit($material->description, 70) }}</p>
                    </div>
                    <span
                        class="flex-shrink-0 ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $material->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div class="mt-3 text-xs text-gray-600">
                    <span class="font-semibold">Mapel:</span> {{ $material->subject?->name ?? 'N/A' }} |
                    <span class="font-semibold">Kelas:</span> {{ $material->class?->class ?? 'N/A' }}
                </div>
                <div class="flex items-center justify-end mt-4 space-x-4">
                    <button wire:click="view({{ $material->id }})"
                        class="text-sm font-medium text-green-600 hover:text-green-900">Lihat</button>
                    <a href="{{ route('teacher.materials.edit', $material) }}" wire:navigate
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Edit</a>
                    <button wire:click="confirmDelete({{ $material->id }})"
                        class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">Tidak ada materi ditemukan.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $this->materials->links() }}</div>

    <x-ui.confirm-modal title="Hapus Materi"
        message="Anda yakin ingin menghapus materi ini? File yang terlampir juga akan dihapus."
        wireConfirmAction="delete" />

    @if ($viewingMaterial)
        <x-ui.teacher.material-preview-modal :title="$viewingMaterial->title" :description="$viewingMaterial->description" :content="$viewingMaterial->content" :filePath="$viewingMaterial->file_path"
            :materialId="$viewingMaterial->id" :subject="$viewingMaterial->subject?->name" :class="$viewingMaterial->class?->class" />
    @endif
</div>
