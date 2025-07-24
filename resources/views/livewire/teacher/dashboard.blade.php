<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Aktivitas Siswa</h2>
    </x-slot:pageHeader>

    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Total Siswa Aktif</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['activeStudents'] }}</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Total Kuis Dikerjakan</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalAttempts'] }}</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Rata-rata Nilai</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['averageScore'] }}</p>
        </div>
    </div>
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label for="class_filter" class="block text-sm font-medium text-gray-700">Filter Kelas</label>
                <select wire:model.live="classFilter" id="class_filter"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Kelas</option>
                    @foreach ($filters['classes'] as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="subject_filter" class="block text-sm font-medium text-gray-700">Filter Mapel</label>
                <select wire:model.live="subjectFilter" id="subject_filter"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Mapel</option>
                    @foreach ($filters['subjects'] as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="quiz_filter" class="block text-sm font-medium text-gray-700">Filter Kuis</label>
                <select wire:model.live="quizFilter" id="quiz_filter"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Kuis</option>
                    @foreach ($filters['quizzes'] as $quiz)
                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                        Siswa</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Kuis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nilai
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Waktu
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($attempts as $attempt)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->student->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->student->class->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->quiz->title }}</td>
                        <td
                            class="px-6 py-4 whitespace-nowrap font-bold {{ $attempt->score >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->score }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $attempt->created_at->format('d M Y, H:i') }}</td>
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
        {{ $attempts->links() }}
    </div>
</div>
