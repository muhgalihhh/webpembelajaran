<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Nilai Siswa</h2>
    </x-slot:pageHeader>

    {{-- Area Filter dan Tombol Aksi --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-form.select-group label="Pilih Kelas" name="classFilter" wireModel="classFilter" :options="$this->availableClasses"
                optionValue="id" optionLabel="class" placeholder="Pilih Kelas Dahulu" />

            <x-form.select-group label="Pilih Kurikulum" name="kurikulumFilter" wireModel="kurikulumFilter"
                :options="$this->kurikulumOptions" placeholder="Pilih Kurikulum" />

            <x-form.select-group label="Urutkan Berdasarkan" name="sortBy" wireModel="sortBy" :options="['average_score' => 'Nilai Rata-Rata', 'student_name' => 'Nama Siswa']" />

            @if ($classFilter && $kurikulumFilter)
                <x-form.button wire:click="exportExcel" icon="fa-solid fa-file-excel" class="w-full">
                    Export Excel
                </x-form.button>
            @endif
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Siswa</th>
                    @foreach ($this->availableSubjects as $subject)
                        <th scope="col" class="px-6 py-3 text-center">{{ $subject->name }}</th>
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
                            <p>Silakan pilih kelas dan kurikulum untuk melihat daftar nilai.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @if ($this->studentsWithScores->isNotEmpty())
            @foreach ($this->studentsWithScores as $student)
                <div class="p-4 bg-white rounded-lg shadow-md">
                    <div class="flex items-start justify-between pb-3 border-b">
                        <h3 class="font-bold text-gray-800">{{ $student['name'] }}</h3>
                        <div class="ml-4 text-right">
                            <p class="text-2xl font-bold text-blue-600">{{ $student['average_score'] }}</p>
                            <p class="text-xs text-gray-500">Rata-rata</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-2">
                        @foreach ($this->availableSubjects as $subject)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $subject->name }}</span>
                                <span
                                    class="font-semibold text-gray-800">{{ $student['scores'][$subject->name] ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="py-12 text-center text-gray-500 bg-white rounded-lg shadow-md">
                <i class="mb-2 text-4xl fa-solid fa-inbox"></i>
                <p>Silakan pilih kelas dan kurikulum untuk melihat daftar nilai.</p>
            </div>
        @endif
    </div>
</div>
