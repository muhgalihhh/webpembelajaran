<div>
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Tugas</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-form.input-group label="Pencarian Tugas" type="search" wireModel="search" id="search"
                placeholder="Cari judul tugas..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->subjects" placeholder="Semua Mapel" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                placeholder="Semua Kelas" />
            <x-form.select-group label="Filter Status" name="statusFilter" wireModel="statusFilter" :options="['' => 'Semua Status', 'open' => 'Open', 'closed' => 'Closed']" />
            <div class="lg:col-start-4">
                <x-form.button wire:click="create" icon="fa-solid fa-plus" class="w-full">
                    Tambah Tugas
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Tugas --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
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
                        Pembuat Tugas</th>
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
                            <div>{{ $task->subject?->kurikulum ?? 'N/A' }}</div>
                            <div class="text-xs">Kelas {{ $task->class?->class ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $task->creator?->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if ($task->due_date && $task->due_time)
                                {{-- PERBAIKAN: Gunakan accessor untuk format yang konsisten --}}
                                <div class="text-sm">{{ $task->due_date->format('d M Y') }}</div>
                                <div class="text-xs">{{ $task->due_time_formatted }}</div>

                                {{-- PERBAIKAN: Gunakan accessor due_date_time --}}
                                @if (now()->gt($task->due_date_time))
                                    <span class="text-xs text-red-500">Tenggat terlewat</span>
                                @else
                                    <span class="text-xs text-green-500">Tenggat masih berlaku</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Tanpa Batas Waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-blue-100 text-blue-800' => $task->status == 'open',
                                'bg-gray-100 text-gray-800' => $task->status == 'closed',
                            ])>
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                                class="text-blue-600 hover:text-blue-900">Lihat Pengumpulan</a>
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
                    required />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                    optionLabel="class" required />
                <div class="md:col-span-2">
                    <x-form.textarea-group label="Deskripsi / Instruksi Tugas" name="description"
                        wireModel="description" required />
                </div>
                <x-form.input-group label="Tenggat Waktu (Opsional)" type="datetime-local" wireModel="due_time"
                    id="due_time" />
                <x-form.select-group label="Status Tugas" name="status" wireModel="status" :options="['draft' => 'Draft', 'publish' => 'Publish']"
                    required />
                <div class="md:col-span-2">
                    <label for="uploadedFile" class="block text-sm font-medium text-gray-700">File Lampiran
                        (Opsional)</label>
                    <input type="file" id="uploadedFile" wire:model="uploadedFile"
                        class="w-full mt-1 file-input file-input-bordered">
                    @if ($currentFilePath && !$uploadedFile)
                        <div class="mt-1 text-xs text-gray-500">File saat ini: {{ basename($currentFilePath) }}</div>
                    @endif
                </div>
                <div class="md:col-span-2" x-data="{ isPublished: @entangle('is_published').live }">
                    <div class="flex items-center">
                        <input id="is_published" type="checkbox" x-model="isPublished"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_published" class="block ml-2 text-sm text-gray-900">Publikasikan Tugas</label>
                    </div>
                    <div x-show="isPublished" class="mt-4">
                        <x-form.input-group label="Jadwalkan Publikasi (Opsional)" type="datetime-local"
                            wireModel="published_at" id="published_at" />
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
