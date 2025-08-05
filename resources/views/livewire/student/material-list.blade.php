<div class="container max-w-6xl p-4 mx-auto my-8 rounded-lg shadow-lg bg-gradient-to-b from-blue-200 to-blue-300">

    {{-- 1. Header --}}
    @include('livewire.student.partials._header', ['subject' => $subject])

    {{-- 2. Tabs --}}
    <div class="pt-6 sm:pt-8">
        @include('livewire.student.partials._tabs', ['activeTab' => $activeTab])
    </div>

    {{-- 3. Kontainer Konten --}}
    <div class="p-6 rounded-lg bg-blue-50 min-h-96">

        {{-- Tab Materi Teks/PDF --}}
        @if ($activeTab === 'text')
            <div>
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                    @forelse ($textMaterials as $material)
                        <x-ui.student.materials.card :material="$material" context="text"
                            wire:key="text-{{ $material->id }}" />
                    @empty
                        <p class="py-12 text-center text-gray-600 col-span-full">Belum ada materi teks/PDF untuk mata
                            pelajaran ini.</p>
                    @endforelse
                </div>

                {{-- Tautan Paginasi (hanya tampil jika ada lebih dari 1 halaman) --}}
                @if ($textMaterials->hasPages())
                    <div class="pt-8 mt-6 border-t border-blue-200">
                        {{ $textMaterials->links() }}
                    </div>
                @endif
            </div>
        @endif


        {{-- Tab Materi Video --}}
        @if ($activeTab === 'video')
            <div>
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                    @forelse ($videoMaterials as $material)
                        <x-ui.student.materials.card :material="$material" context="video"
                            wire:key="video-{{ $material->id }}" />
                    @empty
                        <p class="py-12 text-center text-gray-600 col-span-full">Belum ada materi video untuk mata
                            pelajaran ini.</p>
                    @endforelse
                </div>

                {{-- Tautan Paginasi (hanya tampil jika ada lebih dari 1 halaman) --}}
                @if ($videoMaterials->hasPages())
                    <div class="pt-8 mt-6 border-t border-blue-200">
                        {{ $videoMaterials->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- 4. Materi Terakhir Diakses --}}
    @if ($lastAccessed->isNotEmpty())
        <div class="p-6 mt-8 bg-white rounded-lg" wire:poll.5s>
            <h2 class="mb-6 text-xl font-bold text-gray-900">Materi Terakhir Diakses</h2>
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($lastAccessed->take(5) as $material)
                    <x-ui.student.materials.last-accessed-card :material="$material" />
                @endforeach
            </div>
        </div>
    @endif
</div>
