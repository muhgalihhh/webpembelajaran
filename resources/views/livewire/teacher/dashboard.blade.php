{{-- Single Root Element untuk Livewire --}}
<div>
    {{-- Header Section --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Guru</h2>
    </x-slot:pageHeader>

    {{-- Statistics Cards with Clean Design --}}
    <div class="grid grid-cols-1 gap-4 mt-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Siswa Aktif Card - ENHANCED VISIBILITY -->
        <div class="relative p-4 overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all duration-300">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-white font-bold text-sm mb-1 tracking-wide uppercase">SISWA AKTIF</p>
                        <p class="text-3xl font-black text-white mb-1">{{ $this->stats['activeStudents'] }}</p>
                        <div class="flex items-center text-white">
                            <div class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-semibold">Online sekarang</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <div class="w-14 h-14 bg-blue bg-opacity-25 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Section -->
                <div class="space-y-2 mt-4">
                    <div class="flex justify-between items-center text-sm text-white">
                        <span class="font-semibold">Target: 50 siswa</span>
                        <span class="bg-white bg-opacity-25 px-3 py-1 rounded-lg font-bold text-blue-700">75%</span>
                    </div>
                    
                    <div class="w-full bg-blue-700 bg-opacity-30 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative elements -->
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-5 rounded-full"></div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-white bg-opacity-5 rounded-full"></div>
        </div>

        <!-- Total Pengerjaan Card - ENHANCED VISIBILITY -->
        <div class="relative p-4 overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all duration-300">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-white font-bold text-sm mb-1 tracking-wide uppercase">TOTAL PENGERJAAN</p>
                        <p class="text-3xl font-black text-white mb-1">{{ $this->stats['totalAttempts'] }}</p>
                        <div class="flex items-center text-white">
                            <div class="w-2 h-2 bg-yellow-300 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-semibold">Hari ini: +5</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <div class="w-14 h-14 bg-gren bg-opacity-25 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-tasks text-xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Chart -->
                <div class="space-y-2 mt-4">
                    <div class="flex justify-between items-center text-sm text-white">
                        <span class="font-semibold">Trend 7 hari</span>
                        <span class="bg-white bg-opacity-25 px-3 py-1 rounded-lg font-bold text-green-700 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-sm"></i>
                            +24%
                        </span>
                    </div>
                    
                    <!-- Simple bar chart -->
                    <div class="flex items-end justify-between h-6 space-x-1">
                        <div class="flex-1 bg-white bg-opacity-40 rounded-t-sm" style="height: 60%;"></div>
                        <div class="flex-1 bg-white bg-opacity-60 rounded-t-sm" style="height: 85%;"></div>
                        <div class="flex-1 bg-white bg-opacity-30 rounded-t-sm" style="height: 45%;"></div>
                        <div class="flex-1 bg-white bg-opacity-50 rounded-t-sm" style="height: 75%;"></div>
                        <div class="flex-1 bg-white bg-opacity-70 rounded-t-sm" style="height: 95%;"></div>
                        <div class="flex-1 bg-white bg-opacity-50 rounded-t-sm" style="height: 68%;"></div>
                        <div class="flex-1 bg-yellow-300 rounded-t-sm" style="height: 100%;"></div>
                    </div>
                    <div class="flex justify-between text-sm text-white font-semibold mt-1">
                        <span class="flex-1 text-center">S</span>
                        <span class="flex-1 text-center">S</span>
                        <span class="flex-1 text-center">R</span>
                        <span class="flex-1 text-center">K</span>
                        <span class="flex-1 text-center">J</span>
                        <span class="flex-1 text-center">S</span>
                        <span class="flex-1 text-center font-black text-yellow-300">M</span>
                    </div>
                </div>
            </div>
            
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-5 rounded-full"></div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-white bg-opacity-5 rounded-full"></div>
        </div>

        <!-- Rata-rata Skor Card - ENHANCED VISIBILITY -->
        <div class="relative p-4 overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all duration-300">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-white font-bold text-sm mb-1 tracking-wide uppercase">RATA-RATA SKOR</p>
                        <p class="text-3xl font-black text-white mb-1">{{ round($this->stats['averageScore']) }}</p>
                        <div class="flex items-center text-white">
                            <div class="w-2 h-2 bg-pink-300 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-semibold">Grade: A</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <div class="relative">
                            <div class="w-14 h-14 bg-purple bg-opacity-25 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-medal text-xl text-white"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 bg-white text-purple-600 text-xs font-black px-2 py-1 rounded-full">
                                {{ round($this->stats['averageScore']) }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Score Distribution -->
                <div class="space-y-2 mt-4">
                    <div class="flex justify-between items-center text-sm text-white">
                        <span class="font-semibold">Distribusi Nilai</span>
                        <span class="bg-white bg-opacity-25 px-3 py-1 rounded-lg font-bold text-purple-700 flex items-center">
                            @if($this->stats['averageScore'] >= 85)
                                <i class="fas fa-star mr-1 text-yellow-300"></i>
                                Excellent
                            @elseif($this->stats['averageScore'] >= 70)
                                <i class="fas fa-thumbs-up mr-1 text-green-300"></i>
                                Good  
                            @else
                                <i class="fas fa-arrow-up mr-1 text-blue-300"></i>
                                Improving
                            @endif
                        </span>
                    </div>
                    
                    <!-- Distribution bars -->
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div>
                            <div class="w-full bg-purple-700 bg-opacity-30 rounded-full h-2 mb-1">
                                <div class="bg-red-400 h-2 rounded-full" style="width: 15%;"></div>
                            </div>
                            <span class="text-sm text-white font-semibold">0-60</span>
                        </div>
                        <div>
                            <div class="w-full bg-purple-700 bg-opacity-30 rounded-full h-2 mb-1">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 35%;"></div>
                            </div>
                            <span class="text-sm text-white font-semibold">61-80</span>
                        </div>
                        <div>
                            <div class="w-full bg-purple-700 bg-opacity-30 rounded-full h-2 mb-1">
                                <div class="bg-green-400 h-2 rounded-full" style="width: 85%;"></div>
                            </div>
                            <span class="text-sm text-white font-semibold">81-100</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-5 rounded-full"></div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-white bg-opacity-5 rounded-full"></div>
        </div>

        <!-- Lihat Nilai Siswa Button Card - FIXED MOBILE LAYOUT -->
        <div class="relative p-4 overflow-hidden bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-lg transform hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
            <a href="{{ route('teacher.rankings') }}" wire:navigate class="relative z-10 block">
                <!-- Header with Icon and Title -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-14 h-14 bg-green bg-opacity-20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-line text-xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-black text-sm leading-tight">LIHAT NILAI</h3>
                            <h3 class="text-white font-black text-sm leading-tight">SISWA</h3>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-right text-sm text-white group-hover:translate-x-0.5 transition-transform duration-300"></i>
                    </div>
                </div>
                
                <!-- Description -->
                <p class="text-white opacity-90 text-xs font-medium mb-4">Ranking & analisis lengkap</p>
                
                <!-- Stats Preview in Simple Layout -->
                <div class="bg-blue bg-opacity-15 rounded-xl p-3">
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div>
                            <div class="text-xs text-white opacity-80 font-medium mb-1">Total</div>
                            <div class="text-lg font-black text-white">{{ $this->stats['activeStudents'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-white opacity-80 font-medium mb-1">Avg</div>
                            <div class="text-lg font-black text-white">{{ round($this->stats['averageScore'] ?? 0) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-white opacity-80 font-medium mb-1">Top</div>
                            <div class="text-lg font-black text-white">
                                <i class="fas fa-trophy text-yellow-300 mr-1"></i>95
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Simple Action Text -->
                <div class="mt-3 text-center">
                    <span class="text-xs font-bold text-white opacity-90">👆 Tap untuk melihat detail lengkap</span>
                </div>
            </a>
            
            <!-- Decorative Elements -->
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white bg-opacity-5 rounded-full"></div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-white bg-opacity-5 rounded-full"></div>
        </div>
    </div>

    {{-- Activities Section --}}
    <div class="p-4 mt-8 bg-white rounded-lg shadow sm:p-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Aktivitas Siswa</h2>
            <p class="text-sm text-gray-500 sm:text-base">Pilih untuk melihat hasil kuis atau tugas yang telah dikerjakan siswa.</p>
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

        {{-- Tabel Hasil Aktivitas (Desktop) --}}
        <div class="hidden mt-4 -mx-6 lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Siswa</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kelas</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Judul Aktivitas</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Mapel</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Skor</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->results as $result)
                            <tr class="hover:bg-gray-50">
                                @if ($activityType === 'quiz')
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->user->name ?? 'Siswa tidak ditemukan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->user->class->class ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $result->quiz->title ?? 'Judul tidak tersedia' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->quiz->subject->name ?? 'Mapel tidak tersedia' }}</td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->student->name ?? 'Siswa tidak ditemukan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->student->class->class ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $result->task->title ?? 'Judul tidak tersedia' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->task->subject->name ?? 'Mapel tidak tersedia' }}</td>
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
                                    <button wire:click="prepareToDelete({{ $result->id }})"
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

        {{-- Tampilan Kartu (Mobile) --}}
        <div class="grid grid-cols-1 gap-4 mt-6 lg:hidden">
            @forelse ($this->results as $result)
                <div class="p-4 bg-white border rounded-lg shadow">
                    @if ($activityType === 'quiz')
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $result->quiz->title ?? 'Judul tidak tersedia' }}</p>
                                <p class="text-sm text-gray-600">{{ $result->user->name ?? 'Siswa tidak ditemukan' }} - Kelas {{ $result->user->class->class ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-2 text-2xl font-bold text-blue-600">{{ round($result->score) }}</span>
                        </div>
                        <div class="flex items-end justify-between mt-3">
                            <div class="text-xs text-gray-500">
                                <p>{{ $result->quiz->subject->name ?? 'Mapel tidak tersedia' }}</p>
                                <p>{{ $result->created_at?->format('d M Y, H:i') ?? 'N/A' }}</p>
                            </div>
                            <button wire:click="prepareToDelete({{ $result->id }})"
                                class="text-red-600 transition-colors duration-150 hover:text-red-800 focus:outline-none">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    @else
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $result->task->title ?? 'Judul tidak tersedia' }}</p>
                                <p class="text-sm text-gray-600">{{ $result->student->name ?? 'Siswa tidak ditemukan' }} - Kelas {{ $result->student->class->class ?? 'N/A' }}</p>
                            </div>
                            <span class="ml-2 text-2xl font-bold text-blue-600">{{ round($result->score) }}</span>
                        </div>
                        <div class="flex items-end justify-between mt-3">
                            <div class="text-xs text-gray-500">
                                <p>{{ $result->task->subject->name ?? 'Mapel tidak tersedia' }}</p>
                                <p>{{ $result->submission_date?->format('d M Y, H:i') ?? 'N/A' }}</p>
                            </div>
                            <button wire:click="prepareToDelete({{ $result->id }})"
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

    <x-ui.confirm-modal title="Konfirmasi Hapus"
        message="Anda yakin ingin menghapus data pengerjaan ini? Tindakan ini tidak dapat dibatalkan."
        wireConfirmAction="delete" />
</div>