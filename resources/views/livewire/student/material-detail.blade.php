<div class="relative w-full" x-data="{ sidebarOpen: false }">

    <button @click="sidebarOpen = true"
        class="fixed z-40 p-2 text-white bg-gray-800 rounded-full shadow-lg top-24 left-4 hover:bg-gray-900">
        <i class="w-6 h-6 fas fa-bars"></i>
    </button>

    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-in-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in-out duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/60"></div>

    <aside
        class="fixed left-0 z-40 flex-shrink-0 h-full pt-4 overflow-y-auto text-black transition-transform duration-300 ease-in-out bg-white rounded-lg shadow-lg top-20 w-72"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-blue-300">
                Daftar Materi
            </h3>
            <button @click="sidebarOpen = false" class="p-1 rounded-full hover:bg-gray-700">
                <i class="w-6 h-6 fas fa-times"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            @foreach ($subjectMaterials as $item)
                <a href="{{ route('student.materials.show', $item) }}" wire:navigate
                    class="flex items-center gap-3 px-3 py-2 text-sm transition-colors duration-200 rounded-md group
                    @if ($item->id === $material->id) bg-blue-600 font-bold text-white @else hover:bg-gray-700 @endif">
                    <i class="w-5 text-center text-blue-300 fas fa-file-alt"></i>
                    <span>{{ $item->title }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="w-full">

        <div class="container w-full p-4 mx-auto my-8">
            <div class="overflow-hidden bg-white border shadow-xl rounded-2xl">
                <div class="px-8 py-6 text-white bg-blue-400 border-b border-black">
                    <h1 class="mb-3 text-4xl font-bold leading-tight">{{ $material->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-blue-100">
                        <div class="flex items-center gap-2">
                            <i class="text-blue-200 fas fa-book"></i>
                            <span class="font-medium">{{ $material->subject->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="text-blue-200 fas fa-bookmark"></i>
                            <span class="font-medium">Bab {{ $material->chapter }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6">
                    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Isi Materi</h2>
                    <div
                        class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-600 prose-strong:text-gray-800">
                        {!! $material->content !!}
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <h3 class="flex items-center gap-2 mb-4 text-xl font-semibold text-gray-800">
                        <i class="text-blue-600 fas fa-folder-open"></i>
                        Sumber Belajar
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @if ($material->file_path)
                            <div
                                class="p-6 transition-all duration-300 border border-black bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] group">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="p-3 transition-colors bg-blue-500 rounded-full group-hover:bg-blue-600">
                                        <i class="text-xl text-white fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Materi PDF</h4>
                                        <p class="text-sm text-gray-600">Unduh dan baca secara offline</p>
                                    </div>
                                </div>
                                <a href="{{ route('materials.view', $material) }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white transition-colors duration-200 transform bg-blue-500 rounded-lg hover:bg-blue-600 hover:scale-105">
                                    <i class="fas fa-eye"></i>
                                    <span>Baca Materi</span>
                                </a>
                            </div>
                        @else
                            <div
                                class="p-6 border bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)]">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="p-3 bg-gray-400 rounded-full">
                                        <i class="text-xl text-white fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-600">Materi PDF</h4>
                                        <p class="text-sm text-gray-500">Belum tersedia</p>
                                    </div>
                                </div>
                                <div
                                    class="inline-flex items-center gap-2 px-4 py-2 font-medium text-gray-600 bg-gray-300 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Tidak Tersedia</span>
                                </div>
                            </div>
                        @endif

                        @if ($material->youtube_url)
                            <div
                                class="p-6 transition-all duration-300 border border-red-black bg-gradient-to-br from-red-50 to-red-100 rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] group">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="p-3 transition-colors bg-red-500 rounded-full group-hover:bg-red-600">
                                        <i class="text-xl text-white fab fa-youtube"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Video Pembelajaran</h4>
                                        <p class="text-sm text-gray-600">Tonton penjelasan lengkap</p>
                                    </div>
                                </div>
                                <a href="{{ $material->youtube_url }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white transition-colors duration-200 transform bg-red-500 rounded-lg hover:bg-red-600 hover:scale-105">
                                    <i class="fas fa-play"></i>
                                    <span>Tonton Video</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    @if ($material->youtube_url)
                        <div class="mt-8">
                            <h3 class="flex items-center gap-2 mb-4 text-xl font-semibold text-gray-800">
                                <i class="text-red-600 fas fa-play-circle"></i>
                                Video Pembelajaran
                            </h3>
                            <div
                                class="relative overflow-hidden bg-gray-900 border-2 border-black shadow-lg hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] rounded-2xl group transition-all duration-300 hover:scale-101 hover:translate-0.5">
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $this->extractYoutubeId($material->youtube_url) }}"
                                        frameborder="0" class="w-full h-full min-h-[400px]"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div
                                    class="absolute top-0 left-0 w-full h-full transition-opacity duration-300 opacity-0 pointer-events-none bg-gradient-to-t from-black/20 to-transparent hover:opacity-100">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-8 py-4 border-t border-gray-100 bg-gray-50">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="text-blue-500 fas fa-info-circle"></i>
                            <span>Materi pembelajaran untuk {{ $material->subject->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="text-gray-400 fas fa-clock"></i>
                            <span>Diperbarui: {{ $material->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
