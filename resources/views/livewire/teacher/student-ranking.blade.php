<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Nilai Siswa</h2>
    </x-slot:pageHeader>

    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="flex-grow text-2xl font-bold text-gray-800">
                Daftar Nilai Siswa
            </div>
            <div class="flex flex-wrap items-end gap-4">
                <x-form.select-group label="Pilih Kelas" name="classFilter" wireModel="classFilter" :options="$this->availableClasses"
                    optionValue="id" optionLabel="class" placeholder="Pilih Kelas" />

                <x-form.select-group label="Pilih Kurikulum" name="kurikulumFilter" wireModel="kurikulumFilter"
                    :options="$this->kurikulumOptions" placeholder="Pilih Kurikulum" />

                <x-form.select-group label="Urutkan Berdasarkan" name="sortBy" wireModel="sortBy" :options="['average_score' => 'Nilai Rata-Rata', 'student_name' => 'Nama Siswa']" />

                <x-form.button wire:click="exportExcel" icon="fa-solid fa-file-excel">
                    Export Excel
                </x-form.button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Siswa</th>
                    @foreach ($this->availableSubjects as $subject)
                        <th scope="col" class="px-6 py-3 text-center">{{ $subject->name }} -
                            {{ $subject->kurikulum }} </th>
                    @endforeach
                    <th scope="col" class="px-6 py-3 text-center">Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @if ($this->studentsWithScores->isNotEmpty())
                    @foreach ($this->studentsWithScores as $student)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $student['name'] }}
                            </th>
                            @foreach ($this->availableSubjects as $subject)
                                <td class="px-6 py-4 text-center">
                                    {{ $student['scores'][$subject->name] ?? '-' }}

                                </td>
                            @endforeach
                            <td class="px-6 py-4 font-bold text-center text-blue-600">
                                {{ $student['average_score'] }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($this->availableSubjects) + 2 }}" class="py-12 text-center text-gray-500">
                            <i class="mb-2 text-4xl fa-solid fa-inbox"></i>
                            <p>Silakan pilih kelas dan kurikulum untuk melihat daftar nilai.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
