<div>
    {{-- Header dan Statistik (Tidak berubah) --}}
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard Guru</h1>
    <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-3">
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
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $this->stats['averageScore'] }}</p>
        </div>
    </div>

    {{-- Bagian Aktivitas Siswa --}}
    <div class="p-6 mt-8 bg-white rounded-lg shadow">
        {{-- Filter Utama dan Dropdown (Tidak berubah) --}}
        <div>
            <h2 class="text-xl font-bold text-gray-800">Aktivitas Siswa</h2>
            <p class="text-gray-500">Pilih untuk melihat hasil kuis atau tugas yang telah dikerjakan siswa.</p>
            <div class="inline-flex mt-4 overflow-hidden bg-white border-2 border-gray-300 rounded-lg shadow-sm">
                <button wire:click="setActivityType('quiz')"
                    class="px-6 py-2 font-semibold text-gray-700 transition-colors duration-200 focus:outline-none {{ $activityType === 'quiz' ? 'bg-blue-500 text-white shadow-inner' : 'hover:bg-gray-100' }}">
                    <i class="mr-2 fas fa-file-alt"></i> Aktivitas Quiz
                </button>
                <button wire:click="setActivityType('tugas')"
                    class="px-6 py-2 font-semibold text-gray-700 transition-colors duration-200 border-l border-gray-300 focus:outline-none {{ $activityType === 'tugas' ? 'bg-blue-500 text-white shadow-inner' : 'hover:bg-gray-100' }}">
                    <i class="mr-2 fas fa-clipboard-check"></i> Aktivitas Tugas
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3">
            <x-form.input-group label="Pencarian" name="searchQuery" wire:model.live.debounce.300ms="searchQuery"
                placeholder="Cari nama kuis/tugas..." type="search" />
            <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->filterOptions['classes']"
                placeholder="Semua Kelas" optionLabel="class" />
            <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                :options="$this->filterOptions['subjects']" placeholder="Semua Mata Pelajaran" optionLabel="name" />
        </div>

        {{-- Tabel Hasil Aktivitas --}}
        <div class="mt-4 -mx-6">
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
                            {{-- =============================================== --}}
                            {{-- ==== KOLOM AKSI BARU DITAMBAHKAN DI SINI ==== --}}
                            {{-- =============================================== --}}
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
                                {{-- ============================================== --}}
                                {{-- ==== TOMBOL HAPUS DITAMBAHKAN DI SINI ==== --}}
                                {{-- ============================================== --}}
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
            </div>

            <div class="px-6 py-4">
                {{ $this->results->links() }}
            </div>
        </div>
    </div>
</div>
