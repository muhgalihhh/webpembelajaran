<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Aktivitas Siswa</h2>
    </x-slot:pageHeader>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Total Siswa Aktif</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $this->stats['activeStudents'] }}</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Total Kuis Dikerjakan</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $this->stats['totalAttempts'] }}</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Rata-rata Nilai</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $this->stats['averageScore'] }}</p>
        </div>
    </div>

    {{-- Area Filter --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.select-group label="Filter Kelas" name="classFilter" wireModel="classFilter" :options="$this->filterOptions['classes']"
                    wire:model.live='classFilter' optionLabel="class" />
            </div>
            <div class="flex-1">
                <x-form.select-group label="Filter Mapel" name="subjectFilter" wireModel="subjectFilter"
                    wire:model.live='subjectFilter' :options="$this->filterOptions['subjects']" />
            </div>
            <div class="flex-1">
                <x-form.input-group label="Pencarian Kuis" type="search" wireModel="quizSearch" id="quizSearch"
                    placeholder="Cari nama kuis..." icon="fa-solid fa-search" />
            </div>
        </div>
    </div>

    {{-- Tabel Hasil Kuis --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('user_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama Siswa</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas
                    </th>
                    <th wire:click="sortBy('quiz_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama Kuis</th>
                    <th wire:click="sortBy('score')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nilai</th>
                    <th wire:click="sortBy('created_at')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Waktu</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->attempts as $attempt)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->student->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->student->class->class ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->quiz->title }}</td>
                        <td
                            class="px-6 py-4 whitespace-nowrap font-bold {{ $attempt->score >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->score }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $attempt->created_at->format('d M Y, H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada data hasil kuis yang
                            ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $this->attempts->links() }}
    </div>
</div>
