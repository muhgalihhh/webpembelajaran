<div class="relative w-full" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    {{-- Latar belakang overlay untuk tampilan mobile, aktif saat sidebar terbuka --}}
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-in-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in-out duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden">
    </div>

    {{-- Tombol toggle untuk mobile (hanya muncul saat sidebar tersembunyi di mobile) --}}
    <button x-show="!sidebarOpen" x-transition @click="sidebarOpen = true"
        class="fixed z-50 p-3 text-white bg-blue-600 rounded-full shadow-lg lg:hidden top-24 left-4 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <i class="w-5 h-5 fas fa-bars"></i>
    </button>

    <aside
        class="fixed left-0 z-40 flex-shrink-0 h-full pt-4 overflow-y-auto text-black transition-all duration-300 ease-in-out bg-white rounded-lg shadow-lg top-20"
        x-show="sidebarOpen || window.innerWidth >= 1024"
        x-transition:enter="transition-transform ease-in-out duration-300 lg:transition-none"
        x-transition:enter-start="-translate-x-full lg:translate-x-0" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform ease-in-out duration-300 lg:transition-none"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full lg:translate-x-0"
        :class="sidebarOpen ? 'w-72' : 'w-72 lg:w-20'">

        <div class="flex items-center p-4 border-b border-gray-200"
            :class="sidebarOpen ? 'justify-between' : 'justify-center'">
            <h3 class="text-lg font-semibold text-gray-800 whitespace-nowrap" x-show="sidebarOpen" x-transition>
                Daftar Materi
            </h3>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-full hover:bg-gray-200 focus:outline-none">
                <i class="w-6 h-6 fas fa-times" x-show="sidebarOpen"></i>
                <i class="w-6 h-6 fas fa-bars" x-show="!sidebarOpen"></i>
            </button>
        </div>

        <nav class="p-2 mt-2 space-y-1">
            @foreach ($subjectMaterials as $item)
                <a href="{{ route('student.materials.show', $item) }}" wire:navigate
                    class="flex items-center gap-4 px-3 py-2 text-sm transition-colors duration-200 rounded-md group"
                    title="{{ $item->title }}"
                    :class="{
                        'bg-blue-600 font-bold text-white shadow-lg': {{ $item->id === $material->id ? 'true' : 'false' }},
                        'text-gray-700 hover:bg-gray-100': {{ $item->id !== $material->id ? 'true' : 'false' }},
                        'justify-center': !sidebarOpen && window.innerWidth >= 1024
                    }">

                    @php
                        $icon = 'fa-file-alt'; // Default
                        $color = 'text-gray-500';
                        if ($item->youtube_url) {
                            $icon = 'fab fa-youtube';
                            $color = 'text-red-500';
                        } elseif (Str::startsWith($item->file_path, 'http')) {
                            $icon = 'fas fa-link';
                            $color = 'text-green-500';
                        } elseif ($item->file_path) {
                            $icon = 'fas fa-file-pdf';
                            $color = 'text-blue-500';
                        } else {
                            $icon = 'fas fa-file-alt';
                            $color = 'text-gray-500';
                        }
                    @endphp
                    <i @class([
                        'flex-shrink-0 w-5 text-center',
                        $icon,
                        $item->id === $material->id ? 'text-white' : $color,
                    ])></i>

                    <span x-show="sidebarOpen" class="whitespace-nowrap" x-transition>{{ $item->title }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- Konten Utama yang padding-nya menyesuaikan lebar sidebar --}}
    <main class="w-full transition-all duration-300" :class="sidebarOpen ? 'lg:pl-72' : 'lg:pl-20'">
        <div class="container w-full p-4 mx-auto my-8">
            <div class="overflow-hidden bg-white border shadow-xl rounded-2xl">
                {{-- Header Materi --}}
                <div class="px-4 py-6 text-white bg-blue-400 border-b border-black sm:px-8">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        {{-- Konten Kiri: Judul dan Info --}}
                        <div>

                            <h1 class="mb-3 text-3xl font-bold leading-tight sm:text-4xl">{{ $material->title }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-blue-100">
                                <div class="flex items-center gap-2">
                                    <i class="text-blue-200 fas fa-book"></i>
                                    <span class="font-medium">{{ $material->subject->name }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="w-full sm:w-auto">
                            <a href="{{ route('student.materials.index', $material->subject->id) }}" wire:navigate
                                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-lg shadow sm:w-auto hover:bg-blue-50">
                                <i class="mr-2 fas fa-arrow-left"></i>
                                Kembali ke Daftar Materi
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Isi Materi --}}
                <div class="px-8 py-6">
                    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Isi Materi</h2>
                    <div
                        class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-600 prose-strong:text-gray-800">
                        {!! $material->content !!}
                    </div>
                </div>

                {{-- Sumber Belajar --}}
                <div class="px-8 pb-8">
                    <h3 class="flex items-center gap-2 mb-4 text-xl font-semibold text-gray-800">
                        <i class="text-blue-600 fas fa-folder-open"></i>
                        Sumber Belajar
                    </h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        @if ($material->file_path)
                            @php
                                $isLink = Str::startsWith($material->file_path, 'http');
                            @endphp
                            <div
                                class="p-6 transition-all duration-300 border border-black rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] group
                                {{ $isLink ? 'bg-gradient-to-br from-green-50 to-green-100' : 'bg-gradient-to-br from-blue-50 to-blue-100' }}">
                                <div class="flex items-center gap-4 mb-3">
                                    <div
                                        class="p-3 transition-colors rounded-full group-hover:bg-opacity-80 {{ $isLink ? 'bg-green-500' : 'bg-blue-500' }}">
                                        <i
                                            class="text-xl text-white fas {{ $isLink ? 'fa-link' : 'fa-file-pdf' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $isLink ? 'Link Eksternal' : 'Materi Tambahan' }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $isLink ? 'Buka tautan di tab baru' : 'Unduh atau baca online' }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="viewFile({{ $material->id }})"
                                    class="inline-flex items-center gap-2 px-4 py-2 font-medium text-white transition-colors duration-200 transform rounded-lg hover:scale-105 {{ $isLink ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600' }}">
                                    <i class="fas {{ $isLink ? 'fa-external-link-alt' : 'fa-eye' }}"></i>
                                    <span>{{ $isLink ? 'Buka Tautan' : 'Lihat Materi' }}</span>
                                </button>
                            </div>
                        @else
                            {{-- Kartu fallback jika tidak ada file/link --}}
                            <div
                                class="p-6 border bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)]">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="p-3 bg-gray-400 rounded-full">
                                        <i class="text-xl text-white fas fa-times-circle"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-600">File Tambahan</h4>
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
                                class="p-6 transition-all duration-300 border border-red-900 bg-gradient-to-br from-red-50 to-red-100 rounded-xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] group">
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
                                class="relative overflow-hidden bg-gray-900 border-2 border-black shadow-lg hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] rounded-2xl group transition-all duration-300 hover:scale-101 hover:translate-y-[-2px]">
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $this->extractYoutubeId($material->youtube_url) }}"
                                        frameborder="0" class="w-full h-full min-h-[400px]"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
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

    {{-- Modal Penampil File --}}
    <x-ui.modal id="file-viewer-modal">
        @if ($fileViewerUrl)
            <div class="p-2">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h2 class="text-lg font-bold text-gray-800 truncate">{{ $fileViewerTitle }}</h2>
                    <button wire:click="closeFileViewer"
                        class="p-1 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-800">&times;</button>
                </div>
                <div class="mt-4">
                    @if ($fileViewerType === 'pdf')
                        <iframe src="{{ $fileViewerUrl }}" class="w-full h-[75vh]" frameborder="0"></iframe>
                    @elseif ($fileViewerType === 'image')
                        <img src="{{ $fileViewerUrl }}" alt="Pratinjau Materi"
                            class="object-contain w-full max-h-[75vh]">
                    @endif
                </div>
                <div class="flex justify-end pt-4 mt-4">
                    <button type="button" wire:click="closeFileViewer"
                        class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tutup</button>
                </div>
            </div>
        @endif
    </x-ui.modal>

    {{-- Modal Penampil Video YouTube --}}
    <x-ui.modal id="youtube-viewer-modal">
        @if ($youtubeViewerUrl)
            <div class="p-2">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h2 class="text-lg font-bold text-gray-800 truncate">{{ $youtubeViewerTitle }}</h2>
                    <button wire:click="closeYouTubeViewer"
                        class="p-1 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-800">&times;</button>
                </div>
                <div class="mt-4">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="{{ $youtubeViewerUrl }}" frameborder="0" class="w-full h-full min-h-[60vh]"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="flex justify-end pt-4 mt-4">
                    <button type="button" wire:click="closeYouTubeViewer"
                        class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tutup</button>
                </div>
            </div>
        @endif
    </x-ui.modal>

</div>
