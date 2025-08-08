<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <div class="flex flex-col w-full sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Pengumpulan</h2>
                <p class="text-sm text-gray-500">Tugas: {{ $task->title }}</p>
            </div>
            <a href="{{ route('teacher.scores.tasks') }}" wire:navigate
                class="inline-flex items-center justify-center px-4 py-2 mt-3 text-sm font-medium text-white bg-blue-500 rounded-lg sm:mt-0 hover:bg-blue-600">
                <i class="mr-2 fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Tugas
            </a>
        </div>
    </x-slot:pageHeader>

    {{-- Tampilan Tabel untuk Desktop --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                        Siswa</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Waktu
                        Pengumpulan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">File
                        Jawaban</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nilai
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->submissions as $submission)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $submission->student->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $submission->created_at->format('d M Y, H:i') }}
                            @if ($task->due_date_time && $submission->created_at->gt($task->due_date_time))
                                <span class="ml-2 text-xs text-red-600">(Terlambat)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($submission->file_path)
                                <button wire:click="viewFile({{ $submission->id }})"
                                    class="text-blue-600 hover:underline">Lihat File</button>
                            @else
                                -
                            @endif
                        </td>
                        <td
                            class="px-6 py-4 font-bold whitespace-nowrap {{ $submission->score ? 'text-gray-800' : 'text-gray-400' }}">
                            {{ $submission->score ?? 'Belum Dinilai' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="scoreTask({{ $submission->id }})"
                                class="text-indigo-600 hover:text-indigo-900">
                                {{ $submission->score ? 'Edit Nilai' : 'Beri Nilai' }}
                            </button>
                            <button wire:click="confirmDelete({{ $submission->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Belum ada siswa yang mengumpulkan
                            tugas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($this->submissions as $submission)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $submission->student->name }}</h3>
                        <p class="text-xs text-gray-500">
                            Mengumpulkan: {{ $submission->created_at->format('d M Y, H:i') }}
                            @if ($task->due_date_time && $submission->created_at->gt($task->due_date_time))
                                <span class="font-bold text-red-600">(Terlambat)</span>
                            @endif
                        </p>
                    </div>
                    <div class="ml-4 text-right">
                        <p class="text-2xl font-bold {{ $submission->score ? 'text-blue-600' : 'text-gray-400' }}">
                            {{ $submission->score ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Nilai</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 mt-3 border-t">
                    <button wire:click="viewFile({{ $submission->id }})"
                        class="text-sm font-medium text-blue-600 hover:underline">
                        <i class="mr-1 fa-solid fa-link"></i> Lihat File
                    </button>
                    <div class="flex items-center space-x-4">
                        <button wire:click="scoreTask({{ $submission->id }})"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            {{ $submission->score ? 'Edit' : 'Nilai' }}
                        </button>
                        <button wire:click="confirmDelete({{ $submission->id }})"
                            class="text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-gray-500 bg-white rounded-lg shadow-md">
                <p>Belum ada siswa yang mengumpulkan tugas ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $this->submissions->links() }}</div>

    {{-- Modal Penilaian --}}
    <x-ui.modal id="score-form-modal">
        <h2 class="text-2xl font-bold">Beri Nilai Tugas</h2>
        <p class="text-sm text-gray-600">Siswa: {{ $scoringSubmission?->student->name }}</p>
        <form wire:submit.prevent="saveScore" class="mt-4">
            <div class="space-y-4">
                <x-form.input-group label="Nilai (0-100)" type="number" wireModel="score" id="score" required />
                <x-form.textarea-group label="Umpan Balik (Opsional)" name="feedback" wireModel="feedback" />
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Penampil File --}}
    <x-ui.modal id="file-viewer-modal">
        @if ($fileViewerUrl)
            <div class="p-2">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h2 class="text-lg font-bold text-gray-800">Jawaban: {{ $viewingFileFor?->student->name }}</h2>
                    <button wire:click="closeFileViewer" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                <div class="mt-4">
                    @if ($fileViewerType === 'pdf')
                        <iframe src="{{ $fileViewerUrl }}" class="w-full h-[75vh]" frameborder="0"></iframe>
                    @elseif ($fileViewerType === 'image')
                        <img src="{{ $fileViewerUrl }}" alt="Jawaban siswa" class="object-contain w-full max-h-[75vh]">
                    @endif
                </div>
                <div class="flex justify-end pt-4 mt-4">
                    <button type="button" wire:click="closeFileViewer"
                        class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tutup</button>
                </div>
            </div>
        @endif
    </x-ui.modal>

    {{-- Modal Konfirmasi Hapus --}}
    <x-ui.confirm-modal title="Hapus Pengumpulan"
        message="Anda yakin ingin menghapus data pengumpulan tugas ini? File yang diunggah siswa juga akan terhapus secara permanen."
        wireConfirmAction="deleteSubmission" />
</div>
