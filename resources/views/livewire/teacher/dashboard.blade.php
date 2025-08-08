<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Dasbor Guru</h2>
    </x-slot:pageHeader>

    {{-- Tombol Lihat Nilai dipindah ke dalam grid statistik untuk layout yang lebih rapi di semua ukuran layar --}}
    <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
            <p class="font-medium text-gray-500 truncate">Siswa Aktif</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->stats['activeStudents'] }}</p>
        </div>
        <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
            <p class="font-medium text-gray-500 truncate">Total Pengerjaan</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->stats['totalAttempts'] }}</p>
        </div>
        <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
            <p class="font-medium text-gray-500 truncate">Rata-rata Skor</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ round($this->stats['averageScore']) }}</p>
        </div>
        {{-- Tombol ini sekarang menjadi bagian dari grid --}}
        <div classa="p-4 flex items-center justify-center overflow-hidden bg-white rounded-lg shadow">
            <a href="{{ route('teacher.rankings') }}" wire:navigate
                class="flex items-center justify-center w-full h-full px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600">
                <i class="mr-2 fa-solid fa-trophy"></i>
                <span>Lihat Nilai Siswa</span>
            </a>
        </div>
    </div>

    {{-- Bagian Aktivitas Siswa --}}
    <div class="p-4 mt-8 bg-white rounded-lg shadow sm:p-6">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Aktivitas Siswa</h2>
            <p class="text-sm text-gray-500 sm:text-base">Pilih untuk melihat hasil kuis atau tugas yang telah
                dikerjakan siswa.</p>
            {{-- Tombol di-wrap agar bisa responsif --}}
            <div class="flex flex-col mt-4 sm:flex-row">
                <button wire:click="setActivityType('quiz')"
                    class="px-6 py-2 font-semibold text-gray-700 transition-colors duration-200 focus:outline-none rounded-t-lg sm:rounded-l-lg sm:rounded-t-none {{ $activityType === 'quiz' ? 'bg-blue-500 text-white shadow-inner' : 'bg-gray-200 hover:bg-gray-300' }}">
                    <i class="mr-2 fas fa-file-alt"></i> Aktivitas Quiz
                </button>
                <button wire:click="setActivityType('tugas')"
                    class="px-6 py-2 font-semibold text-gray-700 transition-colors duration-200 focus:outline-none rounded-b-lg sm:rounded-r-lg sm:rounded-b-none {{ $activityType === 'tugas' ? 'bg-blue-500 text-white shadow-inner' : 'bg-gray-200 hover:bg-gray-300' }}">
                    <i class="mr-2 fas fa-clipboard-check"></i> Aktivitas Tugas
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3">
            <x-form.input-group label="Pencarian" name="searchQuery" wireModel="searchQuery"
                placeholder="Cari nama kuis/tugas..." type="search" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->filterOptions['classes']"
                placeholder="Semua Kelas" optionLabel="class" />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->filterOptions['subjects']" placeholder="Semua Mata Pelajaran" optionLabel="name" />
        </div>

        {{-- Tabel Hasil Aktivitas (Hanya untuk Desktop) --}}
        <div class="hidden mt-4 -mx-6 lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Nama Siswa</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Kelas</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Judul Aktivitas</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Mapel</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Skor</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->results as $result)
                            <tr class="hover:bg-gray-50">
                                @if ($activityType === 'quiz')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $result->user->name ?? 'Siswa tidak ditemukan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->user->class->class ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $result->quiz->title ?? 'Judul tidak tersedia' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $result->quiz->subject->name ?? 'Mapel tidak tersedia' }}</td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $result->student->name ?? 'Siswa tidak ditemukan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $result->student->class->class ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $result->task->title ?? 'Judul tidak tersedia' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $result->task->subject->name ?? 'Mapel tidak tersedia' }}</td>
                                @endif
                                <td class="px-6 py-4 font-bold whitespace-nowrap">{{ round($result->score) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    @if ($activityType === 'quiz')
                                        {{ $result->created_at?->format('d M Y, H:i') ?? 'N/A' }}
                                    @else
                                        {{ $result->submission_date?->format('d M Y, H:i') ?? 'N/A' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button wire:click="deleteAttempt({{ $result->id }})"
                                        wire:confirm="Anda yakin ingin menghapus data pengerjaan ini? Tindakan ini tidak dapat dibatalkan."
                                        class="text-red-600 transition-colors duration-150 hover:text-red-800 focus:outline-none">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-500">
                                    <i class="mb-2 text-4xl fas fa-box-open"></i>
                                    <p>Tidak ada data untuk ditampilkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $this->results->links() }}
                </div>
            </div>
        </div>

        {{-- Tampilan Kartu (Hanya untuk Mobile) --}}
        <div class="grid grid-cols-1 gap-4 mt-6 lg:hidden">
            @forelse ($this->results as $result)
                <div class="p-4 bg-white border rounded-lg shadow">
                    @if ($activityType === 'quiz')
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $result->quiz->title ?? 'Judul tidak tersedia' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $result->user->name ?? 'Siswa tidak ditemukan' }} -
                                    Kelas {{ $result->user->class->class ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-2 text-2xl font-bold text-blue-600">{{ round($result->score) }}</span>
                        </div>
                        <div class="flex items-end justify-between mt-3">
                            <div class="text-xs text-gray-500">
                                <p>{{ $result->quiz->subject->name ?? 'Mapel tidak tersedia' }}</p>
                                <p>{{ $result->created_at?->format('d M Y, H:i') ?? 'N/A' }}</p>
                            </div>
                            <button wire:click="deleteAttempt({{ $result->id }})"
                                wire:confirm="Anda yakin ingin menghapus data pengerjaan ini? Tindakan ini tidak dapat dibatalkan."
                                class="text-red-600 transition-colors duration-150 hover:text-red-800 focus:outline-none">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    @else
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $result->task->title ?? 'Judul tidak tersedia' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $result->student->name ?? 'Siswa tidak ditemukan' }} - Kelas
                                    {{ $result->student->class->class ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-2 text-2xl font-bold text-blue-600">{{ round($result->score) }}</span>
                        </div>
                        <div class="flex items-end justify-between mt-3">
                            <div class="text-xs text-gray-500">
                                <p>{{ $result->task->subject->name ?? 'Mapel tidak tersedia' }}</p>
                                <p>{{ $result->submission_date?->format('d M Y, H:i') ?? 'N/A' }}</p>
                            </div>
                            <button wire:click="deleteAttempt({{ $result->id }})"
                                wire:confirm="Anda yakin ingin menghapus data pengerjaan ini? Tindakan ini tidak dapat dibatalkan."
                                class="text-red-600 transition-colors duration-150 hover:text-red-800 focus:outline-none">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-1 py-10 text-center text-gray-500">
                    <i class="mb-2 text-4xl fas fa-box-open"></i>
                    <p>Tidak ada data untuk ditampilkan.</p>
                </div>
            @endforelse

            <div class="col-span-1 py-4">
                {{ $this->results->links() }}
            </div>
        </div>
    </div>
</div>
