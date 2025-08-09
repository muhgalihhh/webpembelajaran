<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <x-ui.student.container icon="fa fa-trophy" class="bg-white rounded-lg shadow-lg">
        <x-slot:title>
            <h2 class="text-xl font-bold text-gray-800">Halaman Cek Peringkat Siswa</h2>
        </x-slot:title>

        <div class="flex justify-between w-full p-4 mb-6 bg-white rounded-lg shadow-md">

            <h2 class="text-4xl font-semibold text-gray-800">Peringkat Siswa</h2>
            <div class="flex items-center space-x-4">
                <x-form.select-group label="Filter Waktu" name="timeFilter" wireModel="timeFilter" :options="$this->timeFilterOptions"
                    optionLabel="name" placeholder="Semua Waktu" />

                <x-form.select-group label="Filter Mata Pelajaran" name="subjectFilter" wireModel="subjectFilter"
                    :options="$this->subjects" optionLabel="name" placeholder="Semua Mata Pelajaran" class="w-64" />
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Peringkat
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Nama Siswa
                        </th>
                        {{-- Kolom Mata Pelajaran yang baru ditambahkan --}}
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Mata Pelajaran
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata Nilai Quiz
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata Nilai Tugas
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Rata-rata
                        </th>
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
                            {{-- Data kolom Mata Pelajaran yang baru ditambahkan --}}
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $student['subject_name'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $student['quiz_score'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $student['task_score'] }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600 whitespace-nowrap">
                                {{ $student['overall_average'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">
                                Tidak ada data peringkat ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->rankings->links() }}
        </div>
    </x-ui.student.container>
</div>
