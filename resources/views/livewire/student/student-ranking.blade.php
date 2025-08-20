<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <x-ui.student.container icon="fa fa-trophy" class="bg-white rounded-lg shadow-lg">
        <x-slot:title>
            <h2 class="text-xl font-bold text-gray-800">Halaman Cek Peringkat Siswa</h2>
        </x-slot:title>

        {{-- Header dengan Filter Responsif --}}
        <div
            class="flex flex-col w-full gap-4 p-4 mb-6 bg-white rounded-lg shadow-md md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-semibold text-gray-800 md:text-4xl">Peringkat Siswa</h2>

            {{-- Filter ditumpuk di mobile, berdampingan di desktop --}}
            <div class="flex flex-col w-full gap-4 md:flex-row md:items-center md:w-auto md:space-x-4 md:gap-0">
                <div class="w-full md:w-auto">
                    <x-form.select-group label="Filter Waktu" name="timeFilter" wireModel="timeFilter" :options="$this->timeFilterOptions"
                        optionLabel="name" placeholder="Semua Waktu" />
                </div>
                <div class="w-full md:w-64">
                    <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                        :options="$this->subjects" optionLabel="name" placeholder="Semua Mata Pelajaran" />
                </div>
            </div>
        </div>

        {{-- Tampilan Tabel untuk Desktop --}}
        <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Peringkat</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                            Siswa</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Mata
                            Pelajaran</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata Nilai Quiz</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata Nilai Tugas</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->rankings as $student)
                        <tr class="hover:bg-gray-50 @if ($student['id'] === auth()->id()) bg-blue-100 font-bold @endif">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if ($student['rank'] === 1)
                                        🥇
                                    @elseif ($student['rank'] === 2)
                                        🥈
                                    @elseif ($student['rank'] === 3)
                                        🥉
                                    @endif
                                    {{ $student['rank'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student['name'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $student['subject_name'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $student['quiz_score'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $student['task_score'] }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600 whitespace-nowrap">
                                {{ $student['overall_average'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data peringkat
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tampilan Kartu untuk Mobile --}}
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse ($this->rankings as $student)
                <div
                    class="p-4 bg-white rounded-lg shadow-md @if ($student['id'] === auth()->id()) border-2 border-blue-500 @endif">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold text-gray-800">{{ $student['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $student['subject_name'] }}</p>
                        </div>
                        <div class="text-2xl font-bold text-right">
                            @if ($student['rank'] === 1)
                                🥇
                            @elseif ($student['rank'] === 2)
                                🥈
                            @elseif ($student['rank'] === 3)
                                🥉
                            @endif
                            #{{ $student['rank'] }}
                        </div>
                    </div>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Rata-rata Quiz:</span>
                            <span class="font-semibold text-gray-800">{{ $student['quiz_score'] }}</span>
                        </div>
                        <div class="flex justify-between mt-1 text-sm">
                            <span class="text-gray-600">Rata-rata Tugas:</span>
                            <span class="font-semibold text-gray-800">{{ $student['task_score'] }}</span>
                        </div>
                        <div class="flex justify-between mt-2 text-lg">
                            <span class="font-bold text-gray-800">Total Rata-rata:</span>
                            <span class="font-bold text-blue-600">{{ $student['overall_average'] }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow-md">
                    Tidak ada data peringkat ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $this->rankings->links() }}
        </div>
    </x-ui.student.container>
</div>
