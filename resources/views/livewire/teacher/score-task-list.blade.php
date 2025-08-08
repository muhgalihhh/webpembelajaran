<div>
    {{-- Header Halaman --}}
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
                :options="$this->subjects" placeholder="Semua Mapel" optionValue="id" optionLabel="name" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->classes"
                placeholder="Semua Kelas" optionValue="id" optionLabel="class" />
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
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
                            <div class="text-xs">Kelas {{ $task->class?->class ?? 'N/A' }}</div>
                        </td>
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
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-lg font-semibold text-blue-600">{{ $task->submissions_count }}</span>
                                <span class="ml-1 text-sm">Siswa</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-2 fa-solid fa-pen-to-square"></i>
                                Nilai Sekarang
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            Tidak ada tugas yang perlu dinilai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($this->tasks as $task)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $task->subject?->name ?? 'N/A' }} - Kelas
                            {{ $task->class?->class ?? 'N/A' }}</p>
                    </div>
                    <div class="ml-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $task->submissions_count }}</p>
                        <p class="text-xs text-gray-500">Pengumpulan</p>
                    </div>
                </div>
                <div class="pt-3 mt-4 border-t">
                    <div class="text-sm text-gray-500">
                        <span class="font-semibold">Tenggat:</span>
                        @if ($task->due_date_time)
                            {{ $task->due_date_time->format('d M Y, H:i') }}
                            @if ($task->due_date_time->isPast())
                                <span class="ml-1 text-xs text-red-500">(Terlewat)</span>
                            @endif
                        @else
                            Tanpa Batas Waktu
                        @endif
                    </div>
                    <div class="mt-3 text-right">
                        <a href="{{ route('teacher.scores.submissions', $task) }}" wire:navigate
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="mr-2 fa-solid fa-pen-to-square"></i>
                            Nilai Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-500">
                <p>Tidak ada tugas yang perlu dinilai.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $this->tasks->links() }}</div>
</div>
