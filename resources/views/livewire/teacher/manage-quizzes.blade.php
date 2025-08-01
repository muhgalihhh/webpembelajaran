<div>
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kuis</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-form.input-group label="Pencarian Kuis" type="search" wireModel="search" id="search"
                placeholder="Cari judul kuis..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->subjects" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                optionLabel="class" />
            <x-form.select-group label="Filter Status" name="statusFilter" wireModel="statusFilter" :options="['' => 'Semua Status', 'publish' => 'Published', 'draft' => 'Draft']" />
            <div class="lg:col-start-4">
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full">
                    Tambah Kuis
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Kuis --}}
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
                    <th wire:click="sortBy('duration_minutes')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Info</th>
                    <th wire:click="sortBy('total_questions')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Total Soal</th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->quizzes as $quiz)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                            <div class="text-sm text-gray-500">{{ $quiz->category }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div>{{ $quiz->subject?->name ?? 'N/A' }}</div>
                            <div class="text-xs">Kelas {{ $quiz->targetClass?->class ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div>{{ $quiz->duration_minutes }} Menit</div>
                            <div class="text-xs">KKM: {{ $quiz->passing_score }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="text-gray-500">
                                Mulai: {{ $quiz->start_time ? $quiz->start_time->format('d M Y H:i') : 'N/A' }}<br>
                                Selesai: {{ $quiz->end_time ? $quiz->end_time->format('d M Y H:i') : 'N/A' }} <br>
                                Total Soal: {{ $quiz->total_questions ? $quiz->total_questions : '0' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @class([
                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                'bg-green-100 text-green-800' => $quiz->status == 'publish',
                                'bg-yellow-100 text-yellow-800' => $quiz->status == 'draft',
                                'bg-gray-100 text-gray-800' => $quiz->status == 'archived',
                            ])>
                                {{ ucfirst($quiz->status) }}
                            </span>
                        </td>


                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.quizzes.questions', $quiz) }}" wire:navigate
                                class="px-3 py-2 text-white bg-blue-500 rounded-md hover:text-blue-900">
                                <i class="fa-solid fa-question"></i> Kelola
                                Soal</a>
                            <button wire:click="edit({{ $quiz->id }})"
                                class="px-3 py-2 ml-4 text-indigo-600 bg-blue-100 rounded-md hover:text-indigo-900 hover:bg-blue-200">
                                <i class="fa-solid fa-edit"></i> Edit
                            </button>
                            <button wire:click="confirmDelete({{ $quiz->id }})"
                                class="px-3 py-2 ml-4 text-red-600 rounded-md hover:text-red-900 hover:bg-red-100 bg-red-50">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada kuis ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->quizzes->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="quiz-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Kuis</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-form.input-group label="Judul Kuis" type="text" wireModel="title" id="title" required />
                </div>
                <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id" :options="$this->subjects"
                    required />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                    optionLabel="class" required />
                <x-form.select-group label="Kategori" name="category" wireModel="category" :options="[
                    'Ulangan Harian' => 'Ulangan Harian',
                    'Latihan' => 'Latihan',
                ]" required />
                <x-form.select-group label="Status" name="status" wireModel="status" :options="['draft' => 'Draft', 'publish' => 'Published']" required />
                <div class="md:col-span-2">
                    <x-form.textarea-group label="Deskripsi (Opsional)" name="description" wireModel="description" />
                </div>
                <x-form.input-group label="Durasi (Menit)" type="number" wireModel="duration_minutes"
                    id="duration_minutes" required />
                <x-form.input-group label="Nilai KKM" type="number" wireModel="passing_score" id="passing_score"
                    required />
                <x-form.input-group label="Waktu Mulai (Opsional)" type="datetime-local" wireModel="start_time"
                    id="start_time" />
                <x-form.input-group label="Waktu Selesai (Opsional)" type="datetime-local" wireModel="end_time"
                    id="end_time" />
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input id="shuffle_questions" type="checkbox" wire:model="shuffle_questions"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="shuffle_questions" class="block ml-2 text-sm text-gray-900">Acak Soal</label>
                    </div>
                    <div class="flex items-center">
                        <input id="shuffle_options" type="checkbox" wire:model="shuffle_options"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="shuffle_options" class="block ml-2 text-sm text-gray-900">Acak Opsi</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')" class="btn btn-secondary">Batal</button>
                <x-form.button type="submit" class="btn btn-primary" variant="primary" icon="fa-solid fa-save">
                    Simpan Kuis
                </x-form.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Kuis"
        message="Anda yakin ingin menghapus kuis ini? Semua pertanyaan dan hasil pengerjaan siswa yang terkait akan ikut terhapus."
        wireConfirmAction="delete" />
</div>
