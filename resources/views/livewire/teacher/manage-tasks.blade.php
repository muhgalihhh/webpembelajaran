<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Tugas</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <x-form.input-group label="Pencarian Tugas" type="search" wireModel="search" id="search"
                placeholder="Cari judul tugas..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->subjects" placeholder="Semua Mapel" optionValue="id" optionLabel="name" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                placeholder="Semua Kelas" optionValue="id" optionLabel="class" />
            <x-form.select-group label="Filter Status" name="statusFilter" wireModel="statusFilter" :options="['' => 'Semua Status', 'publish' => 'Published', 'draft' => 'Draft']" />
            <div class="sm:col-start-2 lg:col-start-5">
                <x-form.button wire:click="create" icon="fa-solid fa-plus" class="w-full">
                    Tambah Tugas
                </x-form.button>
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
                        Judul</th>
                    <th wire:click="sortBy('subject_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Mapel & Kelas</th>
                    <th wire:click="sortBy('user_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Pembuat</th>
                    <th wire:click="sortBy('due_date')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Tenggat</th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->tasks as $task)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                            <div class="text-xs {{ $task->is_published ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $task->is_published ? 'Published' : 'Draft' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div>{{ $task->subject?->name ?? 'N/A' }}</div>
                            <div class="text-xs">Kelas {{ $task->class?->class ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $task->creator?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if ($task->due_date_time)
                                {{ $task->due_date_time->format('d M Y, H:i') }}
                                @if ($task->due_date_time->isPast())
                                    <span class="ml-1 text-xs text-red-500">(Terlewat)</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Tanpa Batas Waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-green-100 text-green-800' => $task->status == 'publish',
                                'bg-yellow-100 text-yellow-800' => $task->status == 'draft',
                            ])>
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                                class="text-blue-600 hover:text-blue-900">Pengumpulan</a>
                            <button wire:click="edit({{ $task->id }})"
                                class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $task->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada tugas ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 mt-6 lg:hidden">
        @forelse ($this->tasks as $task)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $task->title }}</h3>
                        <p class="text-xs {{ $task->is_published ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $task->is_published ? 'Published' : 'Draft' }}</p>
                    </div>
                    <span @class([
                        'ml-2 flex-shrink-0 px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                        'bg-green-100 text-green-800' => $task->status == 'publish',
                        'bg-yellow-100 text-yellow-800' => $task->status == 'draft',
                    ])>
                        {{ ucfirst($task->status) }}
                    </span>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p><span class="font-semibold">Mapel:</span> {{ $task->subject?->name ?? 'N/A' }} (Kelas
                        {{ $task->class?->class ?? 'N/A' }})</p>
                    <p><span class="font-semibold">Tenggat:</span>
                        @if ($task->due_date_time)
                            {{ $task->due_date_time->format('d M Y, H:i') }}
                        @else
                            Tanpa Batas Waktu
                        @endif
                    </p>
                </div>
                <div class="flex items-center justify-end pt-3 mt-4 space-x-4 border-t">
                    <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                        class="text-sm font-medium text-blue-600 hover:text-blue-900">Pengumpulan</a>
                    <button wire:click="edit({{ $task->id }})"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button wire:click="confirmDelete({{ $task->id }})"
                        class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">Tidak ada tugas ditemukan.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $this->tasks->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="task-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Tugas</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-form.input-group label="Judul Tugas" type="text" wireModel="title" id="title" required />
                </div>
                <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id" :options="$this->subjects"
                    optionValue="id" optionLabel="name" required />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                    optionValue="id" optionLabel="class" required />
                <div class="md:col-span-2">
                    <x-form.textarea-group label="Deskripsi" name="description" wireModel="description" required />
                </div>
                <x-form.input-group label="Tenggat Waktu (Opsional)" type="datetime-local" wireModel="due_time"
                    id="due_time" />

                <x-form.select-group label="Status Publikasi" name="is_published" wireModel="is_published"
                    :options="['0' => 'Draft', '1' => 'Publish']" required />

                @if ($is_published)
                    <div class="md:col-span-2">
                        <x-form.input-group label="Jadwalkan Publikasi (Opsional)" type="datetime-local"
                            wireModel="published_at" id="published_at" />
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label for="uploadedFile" class="block text-sm font-medium text-gray-700">File Lampiran
                        (Opsional)</label>
                    <input type="file" id="uploadedFile" wireModel="uploadedFile"
                        class="w-full mt-1 file-input file-input-bordered">
                    @if ($currentFilePath && !$uploadedFile)
                        <div class="mt-1 text-xs text-gray-500">File saat ini: {{ basename($currentFilePath) }}</div>
                    @endif
                    <div wire:loading wire:target="uploadedFile" class="mt-1 text-xs text-blue-500">Mengunggah...
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Tugas"
        message="Anda yakin ingin menghapus tugas ini? Semua data pengumpulan siswa yang terkait akan ikut terhapus."
        wireConfirmAction="delete" />
</div>
