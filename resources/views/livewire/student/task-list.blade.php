<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;">
    <x-ui.student.container title="Halaman Tugas Siswa">
        <div class="mb-6 border-b border-gray-300">
            <nav
                class="flex flex-col -mb-px space-y-2 text-center border rounded-lg sm:flex-row sm:justify-between sm:px-8 sm:space-y-0 sm:space-x-6">
                <button wire:click="$set('activeTab', 'semua')"
                    class="px-1 py-4 text-base md:text-lg font-medium leading-5 whitespace-nowrap focus:outline-none {{ $activeTab === 'semua' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua Tugas
                </button>
                <button wire:click="$set('activeTab', 'belum')"
                    class="px-1 py-4 text-base md:text-lg font-medium leading-5 whitespace-nowrap focus:outline-none {{ $activeTab === 'belum' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Belum Dikerjakan
                </button>
                <button wire:click="$set('activeTab', 'sudah')"
                    class="px-1 py-4 text-base md:text-lg font-medium leading-5 whitespace-nowrap focus:outline-none {{ $activeTab === 'sudah' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Sudah Dikumpulkan
                </button>
            </nav>
        </div>

        <div
            class="p-4 mb-6 rounded-lg border shadow-[6px_6px_0px_rgba(0,0,0,0.2)] bg-gradient-to-b from-yellow-50 to-yellow-100">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-form.select-group label="Filter Kurikulum" name="kurikulumFilter" wireModel="kurikulumFilter"
                    :options="$this->kurikulumOptions" placeholder="Semua Kurikulum" />
                <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                    :options="$this->subjects" optionValue="id" optionLabel="name" placeholder="Semua Mata Pelajaran" />
            </div>
        </div>

        {{-- Konten --}}
        <div class="p-4 border rounded-lg shadow-inner bg-gradient-to-b from-yellow-100 to-yellow-400">

            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                <div class="flex items-center p-4 border rounded-lg shadow-md bg-gradient-to-b from-red-100 to-red-200">
                    <div class="p-3 mr-4 text-red-500 bg-white rounded-full">
                        <i class="text-3xl fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="font-bold text-red-800">Belum Dikerjakan</p>
                        <p class="text-2xl font-bold text-red-900">{{ $this->stats['not_submitted'] }} Tugas</p>
                    </div>
                </div>
                <div
                    class="flex items-center p-4 border rounded-lg shadow-md bg-gradient-to-b from-green-100 to-green-200">
                    <div class="p-3 mr-4 text-green-500 bg-white rounded-full">
                        <i class="text-3xl fa-solid fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="font-bold text-green-800">Sudah Dikumpulkan</p>
                        <p class="text-2xl font-bold text-green-900">{{ $this->stats['submitted'] }} Tugas</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($this->tasks as $task)
                    @php
                        $submission = $task->submissions->first();
                        $isSubmitted = (bool) $submission;
                        $isOverdue = !$isSubmitted && $task->due_date_time?->isPast();
                    @endphp
                    <div
                        class="flex flex-col p-4 bg-white border-2 border-gray-400 rounded-lg shadow-sm md:flex-row md:items-center">
                        <div class="flex-grow">
                            <div class="flex flex-col mb-2 md:flex-row md:flex-wrap md:items-center">
                                <span @class([
                                    'px-3 py-1 text-xs font-bold text-white rounded-full self-start mb-2 md:mb-0',
                                    'bg-red-500' => !$isSubmitted,
                                    'bg-green-500' => $isSubmitted,
                                ])>
                                    {{ $isSubmitted ? 'Sudah Dikumpulkan' : 'Belum Dikerjakan' }}
                                </span>
                                <span class="mx-0 my-2 text-sm text-gray-600 md:my-0 md:mx-3">Mata Pelajaran:
                                    {{ $task->subject->name }}</span>
                                @if (!$isSubmitted && $task->due_date_time)
                                    <span
                                        class="text-sm font-bold md:ml-auto {{ $isOverdue ? 'text-red-600' : 'text-gray-700' }}">
                                        Tenggat: {{ $task->due_date_time->format('d F Y, H:i') }}
                                    </span>
                                @elseif (!$isSubmitted)
                                    <span class="text-sm font-bold text-gray-700 md:ml-auto">Tidak Ada Tenggat</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-700">{{ $task->description }}</p>
                        </div>
                        <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-6">
                            @if ($isSubmitted)
                                <button type="button" wire:click="viewSubmission({{ $task->id }})"
                                    class="w-full px-6 py-2 font-bold text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 md:w-auto">
                                    Cek Nilai
                                </button>
                            @else
                                <button type="button" wire:click="openSubmissionModal({{ $task->id }})"
                                    class="w-full px-6 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 md:w-auto disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    {{ $isOverdue ? 'disabled' : '' }}>
                                    {{ $isOverdue ? 'Tugas Telat' : 'Upload Tugas' }}
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-500">
                        <i class="mb-4 text-5xl fa-solid fa-folder-open"></i>
                        <p class="font-semibold">Tidak ada tugas yang sesuai dengan filter Anda.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $this->tasks->links() }}
            </div>
        </div>
    </x-ui.student.container>

    {{-- Modal Pengumpulan Tugas --}}
    <x-ui.modal id="submission-modal">
        <form wire:submit.prevent="submitTask">
            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <label for="submissionFile"
                    class="flex flex-col items-center justify-center w-full h-48 px-4 py-6 tracking-wide text-blue-500 uppercase transition-all duration-150 ease-linear bg-gray-100 border-2 border-dashed rounded-lg cursor-pointer hover:bg-blue-100 hover:border-blue-500">
                    <i class="mb-2 text-4xl fa-solid fa-cloud-arrow-up"></i>
                    <span class="mt-2 text-base leading-normal">Klik untuk Upload File Tugas</span>
                    <p class="text-xs text-gray-500">Pilih Salah Satu File/Gambar/Doc/File Lain</p>
                    <input type="file" id="submissionFile" wire:model="submissionFile" class="hidden">
                </label>

                <div x-show="isUploading" class="w-full mt-2 bg-gray-200 rounded-full">
                    <div class="py-1 text-xs font-medium leading-none text-center text-white bg-blue-500 rounded-full"
                        :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                </div>

                @if ($submissionFile)
                    <div class="mt-2 text-sm text-green-600">
                        File dipilih: {{ $submissionFile->getClientOriginalName() }}
                    </div>
                @endif
                @error('submissionFile')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <x-form.textarea-group label="Catatan (Opsional)" name="submissionNotes" wireModel="submissionNotes"
                class="mt-4" />

            <div class="flex justify-between pt-4 mt-4 border-t-2 border-dashed">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-6 py-2 font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Kembali</button>
                <button type="submit"
                    class="px-6 py-2 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">Upload
                    Tugas</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Lihat Nilai --}}
    <x-ui.modal id="view-submission-modal">
        @if ($viewingSubmission)
            <div class="space-y-4">
                <div>
                    <h4 class="font-bold">Status Pengumpulan:</h4>
                    @php
                        $isLate =
                            $viewingSubmission->task->due_date_time &&
                            $viewingSubmission->created_at->gt($viewingSubmission->task->due_date_time);
                    @endphp
                    <span @class([
                        'px-3 py-1 text-sm font-semibold rounded-full',
                        'bg-red-100 text-red-800' => $isLate,
                        'bg-green-100 text-green-800' => !$isLate,
                    ])>
                        {{ $isLate ? 'Telat' : 'Tepat Waktu' }}
                    </span>
                </div>
                <div>
                    <h4 class="font-bold">Nilai Kamu:</h4>
                    <p class="text-3xl font-bold {{ $viewingSubmission->score ? 'text-blue-600' : 'text-gray-500' }}">
                        {{ $viewingSubmission->score ?? 'Belum Dinilai' }}
                    </p>
                </div>
                <div>
                    <h4 class="font-bold">File yang Dikumpulkan:</h4>
                    <button type="button" wire:click="viewFile({{ $viewingSubmission->id }})"
                        class="text-blue-500 hover:underline">
                        <i class="mr-1 fa-solid fa-file"></i>
                        Cek File Jawaban
                    </button>
                </div>
                @if ($viewingSubmission->notes)
                    <div>
                        <h4 class="font-bold">Catatan Kamu:</h4>
                        <p class="p-2 bg-gray-100 rounded-md">{{ $viewingSubmission->notes }}</p>
                    </div>
                @endif
                @if ($viewingSubmission->feedback)
                    <div>
                        <h4 class="font-bold">Feedback dari Guru:</h4>
                        <p class="p-2 bg-green-100 rounded-md">{{ $viewingSubmission->feedback }}</p>
                    </div>
                @endif
            </div>
            <div class="flex justify-end pt-4 mt-4 border-t">
                <button type="button" @click="$dispatch('close-modal')" class="btn btn-secondary">Tutup</button>
            </div>
        @endif
    </x-ui.modal>

    {{-- Modal Penampil File (File Viewer) --}}
    <x-ui.modal id="file-viewer-modal">
        @if ($fileViewerUrl)
            <div class="p-2">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h2 class="text-lg font-bold text-gray-800 truncate">{{ $fileViewerTitle }}</h2>
                    <button wire:click="closeFileViewer"
                        class="p-1 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-800">&times;</button>
                </div>
                <div class="mt-4">
                    @if ($fileViewerType === 'pdf')
                        <iframe src="{{ $fileViewerUrl }}" class="w-full h-[75vh]" frameborder="0"></iframe>
                    @elseif ($fileViewerType === 'image')
                        <img src="{{ $fileViewerUrl }}" alt="File Jawaban"
                            class="object-contain w-full max-h-[75vh]">
                    @endif
                </div>
                <div class="flex justify-end pt-4 mt-4">
                    <button type="button" wire:click="closeFileViewer"
                        class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tutup</button>
                </div>
            </div>
        @endif
    </x-ui.modal>

</div>
