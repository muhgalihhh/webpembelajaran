<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kuis</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <x-form.input-group label="Pencarian Kuis" type="search" wireModel="search" id="search"
                placeholder="Cari judul kuis..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->subjects" placeholder="Semua Mata Pelajaran" optionLabel="name" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                optionLabel="class" placeholder="Semua Kelas" />
            <x-form.select-group label="Filter Status" name="statusFilter" wireModel="statusFilter" :options="['' => 'Semua Status', 'publish' => 'Published', 'draft' => 'Draft']"
                placeholder="Semua Status" />
            <div class="sm:col-start-2 lg:col-start-5">
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full">
                    Tambah Kuis
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
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        Judul</th>
                    <th wire:click="sortBy('subject_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        Mapel & Kelas</th>
                    <th wire:click="sortBy('duration_minutes')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        Info</th>
                    <th wire:click="sortBy('total_questions')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        Jadwal & Soal</th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->quizzes as $quiz)
                    <tr class="transition-colors duration-200 hover:bg-gray-50">
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
                                @if ($quiz->start_time)
                                    <div class="text-xs">Mulai: {{ $quiz->start_date->format('d M Y, H:i') }}</div>
                                @endif
                                @if ($quiz->end_time)
                                    <div class="text-xs">Selesai: {{ $quiz->end_date->format('d M Y, H:i') }}</div>
                                @endif
                                <div class="font-semibold text-blue-600">{{ $quiz->total_questions ?? '0' }} soal</div>
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
                            <div class="flex space-x-2">
                                <a href="{{ route('teacher.quizzes.questions', $quiz) }}" wire:navigate
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">Kelola
                                    Soal</a>
                                <button wire:click="edit({{ $quiz->id }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-700 transition-colors bg-indigo-100 rounded-md hover:bg-indigo-200">Edit</button>
                                <button wire:click="confirmDelete({{ $quiz->id }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 transition-colors bg-red-100 rounded-md hover:bg-red-200">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <p>Tidak ada kuis ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 mt-6 lg:hidden">
        @forelse ($this->quizzes as $quiz)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $quiz->title }}</h3>
                        <p class="text-xs text-gray-500">{{ $quiz->category }}</p>
                    </div>
                    <span @class([
                        'ml-2 flex-shrink-0 px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                        'bg-green-100 text-green-800' => $quiz->status == 'publish',
                        'bg-yellow-100 text-yellow-800' => $quiz->status == 'draft',
                    ])>
                        {{ ucfirst($quiz->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 mt-3 text-sm text-gray-600 gap-x-4">
                    <div><span class="font-semibold">Mapel:</span> {{ $quiz->subject?->name ?? 'N/A' }}</div>
                    <div><span class="font-semibold">Kelas:</span> {{ $quiz->targetClass?->class ?? 'N/A' }}</div>
                    <div><span class="font-semibold">Durasi:</span> {{ $quiz->duration_minutes }} menit</div>
                    <div><span class="font-semibold">KKM:</span> {{ $quiz->passing_score }}</div>
                    <div class="col-span-2"><span class="font-semibold">Soal:</span>
                        {{ $quiz->total_questions ?? '0' }}</div>
                </div>
                <div class="flex flex-wrap justify-end gap-2 mt-4">
                    <a href="{{ route('teacher.quizzes.questions', $quiz) }}" wire:navigate
                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">Kelola
                        Soal</a>
                    <button wire:click="edit({{ $quiz->id }})"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-700 transition-colors bg-indigo-100 rounded-md hover:bg-indigo-200">Edit</button>
                    <button wire:click="confirmDelete({{ $quiz->id }})"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 transition-colors bg-red-100 rounded-md hover:bg-red-200">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-500">
                <p>Tidak ada kuis ditemukan</p>
            </div>
        @endforelse
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
                <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id"
                    :options="$this->subjects" required optionLabel="name" />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                    optionLabel="class" required />
                <x-form.select-group label="Kategori" name="category" wireModel="category" :options="['Ulangan Harian' => 'Ulangan Harian', 'Latihan' => 'Latihan']"
                    required />
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
                <div class="flex items-center mt-2 space-x-6 md:col-span-2">
                    <div class="flex items-center">
                        <input id="shuffle_questions" type="checkbox" wireModel="shuffle_questions"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="shuffle_questions" class="block ml-2 text-sm text-gray-900">Acak Soal</label>
                    </div>
                    <div class="flex items-center">
                        <input id="shuffle_options" type="checkbox" wireModel="shuffle_options"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="shuffle_options" class="block ml-2 text-sm text-gray-900">Acak Opsi</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                <x-form.button type="submit" variant="primary" icon="fa-solid fa-save">Simpan Kuis</x-form.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Kuis"
        message="Anda yakin ingin menghapus kuis ini? Semua pertanyaan dan hasil pengerjaan siswa yang terkait akan ikut terhapus."
        wireConfirmAction="delete" />
</div>
