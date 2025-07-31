<div>
    <x-slot:pageHeader>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Pengumpulan Tugas</h2>
                <p class="text-sm text-gray-500">Tugas: {{ $task->title }}</p>
            </div>
            <a href="{{ route('teacher.scores.tasks') }}" wire:navigate class="btn btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Tugas
            </a>
        </div>
    </x-slot:pageHeader>

    {{-- Tabel Pengumpulan --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Siswa
                    </th>
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
                            {{ $submission->submission_date->format('d M Y, H:i') }}
                            @if ($submission->submission_date > $task->due_time)
                                <span class="ml-2 text-xs text-red-600">(Terlambat)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($submission->file_path)
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:underline">Lihat File</a>
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

    <div class="mt-4">{{ $this->submissions->links() }}</div>

    <x-ui.modal id="score-form-modal">
        <h2 class="text-2xl font-bold">Beri Nilai Tugas</h2>
        <p class="text-sm text-gray-600">Siswa: {{ $scoringSubmission?->student->name }}</p>
        <form wire:submit.prevent="saveScore" class="mt-4">
            <div class="space-y-4">
                <x-form.input-group label="Nilai (0-100)" type="number" wireModel="score" id="score" required />
                <x-form.textarea-group label="Umpan Balik (Opsional)" name="feedback" wireModel="feedback" />
            </div>
            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <x-form.button type="button" class="btn btn-secondary" wireClick="closeModal" variant="secondary">
                    <i class="mr-1 fa-solid fa-xmark"></i> Tutup
                </x-form.button>
                <x-form.button type="submit" class="btn btn-primary">
                    <i class="mr-1 fa-solid fa-check"></i> Simpan Nilai
                </x-form.button>
            </div>
        </form>
    </x-ui.modal>
</div>
