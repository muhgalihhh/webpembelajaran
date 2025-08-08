<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kuis</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-form.input-group label="Pencarian Kuis" type="search" wireModel="search" id="search"
                placeholder="Cari judul kuis..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter.live"
                :options="$this->subjects" placeholder="Semua Mata Pelajaran" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter.live" :options="$this->classes"
                optionLabel="class" placeholder="Semua Kelas" />
            <x-form.select-group label="Filter Status" name="statusFilter" wireModel="statusFilter.live"
                :options="['' => 'Semua Status', 'publish' => 'Published', 'draft' => 'Draft']" placeholder="Semua Status" />

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
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center space-x-1">
                            <span>Judul</span>
                            @if ($sortBy === 'title')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('subject_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center space-x-1">
                            <span>Mapel & Kelas</span>
                            @if ($sortBy === 'subject_id')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('duration_minutes')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center space-x-1">
                            <span>Info</span>
                            @if ($sortBy === 'duration_minutes')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('total_questions')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center space-x-1">
                            <span>Total Soal</span>
                            @if ($sortBy === 'total_questions')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center space-x-1">
                            <span>Status</span>
                            @if ($sortBy === 'status')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
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
                                    <div class="text-xs">
                                        Mulai: {{ $quiz->start_date->format('d/m/Y') }}
                                    </div>
                                @endif
                                @if ($quiz->end_time)
                                    <div class="text-xs">
                                        Selesai: {{ $quiz->end_date->format('d/m/Y') }}
                                    </div>
                                @endif
                                <div class="font-semibold text-blue-600">
                                    {{ $quiz->total_questions ?? '0' }} soal
                                </div>
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
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">
                                    <i class="mr-1 fa-solid fa-question"></i>
                                    Kelola Soal
                                </a>
                                <button wire:click="edit({{ $quiz->id }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-700 transition-colors bg-indigo-100 rounded-md hover:bg-indigo-200">
                                    <i class="mr-1 fa-solid fa-edit"></i>
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $quiz->id }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 transition-colors bg-red-100 rounded-md hover:bg-red-200">
                                    <i class="mr-1 fa-solid fa-trash"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="mb-2 text-4xl text-gray-300 fa-solid fa-clipboard-question"></i>
                                <p class="text-lg font-medium">Tidak ada kuis ditemukan</p>
                                <p class="text-sm">Coba ubah filter atau tambah kuis baru</p>
                            </div>
                        </td>
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
                    <x-form.input-group label="Judul Kuis" type="text" wireModel="title" id="title"
                        required />
                </div>
                <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id"
                    :options="$this->subjects" required />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                    optionLabel="class" required />
                <x-form.select-group label="Kategori" name="category" wireModel="category" :options="[
                    'Ulangan Harian' => 'Ulangan Harian',
                    'Latihan' => 'Latihan',
                ]"
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
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-6">
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
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                <x-form.button type="submit" variant="primary" icon="fa-solid fa-save">
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
