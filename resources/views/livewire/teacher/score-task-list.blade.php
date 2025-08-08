<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Penilaian Tugas</h2>
    </x-slot:pageHeader>

    {{-- Area Filter --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <x-form.input-group label="Pencarian Tugas" type="search" wireModel="search" id="search"
                placeholder="Cari judul tugas..." />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->subjects" placeholder="Semua Mapel" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                placeholder="Semua Kelas" />
        </div>
    </div>

    {{-- Tabel Daftar Tugas --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Judul
                        Tugas</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Mapel &
                        Kelas</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tenggat
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Pengumpulan</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->tasks as $task)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div>{{ $task->subject?->name ?? 'N/A' }}</div>
                            <div>{{ $task->subject?->kurikulum ?? 'N/A' }}</div>
                            <div class="text-xs">Kelas {{ $task->class?->class ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if ($task->due_date && $task->due_time)
                                <div class="text-sm">{{ $task->due_date->format('d M Y') }}</div>
                                <div class="text-xs">{{ $task->due_time_formatted }}</div>

                                {{-- Status tenggat --}}
                                @if (now()->gt($task->due_date_time))
                                    <span class="text-xs text-red-500">Terlewat</span>
                                @else
                                    <span class="text-xs text-green-500">Aktif</span>
                                @endif
                            @elseif ($task->due_date)
                                {{-- Jika hanya ada tanggal tanpa waktu --}}
                                <div class="text-sm">{{ $task->due_date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">Tanpa jam</div>
                            @else
                                <span class="text-xs text-gray-400">Tanpa Batas Waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div class="flex items-center">
                                <span
                                    class="text-lg font-semibold text-blue-600">{{ $task->submissions->count() }}</span>
                                <span class="ml-1 text-sm">Siswa</span>
                            </div>
                            @if ($task->submissions->count() > 0)
                                <div class="text-xs text-gray-400">sudah mengumpulkan</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 rounded-full bg-blue-50 hover:bg-blue-100 hover:text-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Tidak ada tugas yang perlu dinilai.</p>
                                <p class="text-xs text-gray-400">Buat tugas baru atau ubah status tugas menjadi publish
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->tasks->links() }}</div>
</div>
