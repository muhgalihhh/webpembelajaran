<div class="container max-w-6xl p-4 mx-auto my-8 rounded-lg shadow-lg bg-gradient-to-b from-blue-200 to-blue-300">

    {{-- 1. Memanggil Header --}}
    @include('livewire.student.partials._header', ['subject' => $subject])

    <div class="pt-6 sm:pt-8">
        @include('livewire.student.partials._tabs', ['activeTab' => $activeTab])

    </div>

    {{-- 3. Kontainer Konten --}}
    <div class="p-6 rounded-lg bg-blue-50 min-h-96">
        {{-- Tab Materi Teks/PDF --}}
        <div x-show="$wire.activeTab === 'text'" class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
            @forelse ($textMaterials as $material)
                {{-- [FIX] Mengirim 'context' agar tampilan kartu sesuai --}}
                <x-ui.student.materials.card :material="$material" context="text" wire:key="text-{{ $material->id }}" />
            @empty
                <p class="py-12 text-center text-gray-600 col-span-full">Belum ada materi PDF untuk mata pelajaran ini.
                </p>
            @endforelse
        </div>

        {{-- Tab Materi Video --}}
        <div x-show="$wire.activeTab === 'video'" class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
            @forelse ($videoMaterials as $material)
                {{-- [FIX] Mengirim 'context' agar tampilan kartu sesuai --}}
                <x-ui.student.materials.card :material="$material" context="video" wire:key="video-{{ $material->id }}" />
            @empty
                <p class="py-12 text-center text-gray-600 col-span-full">Belum ada materi video untuk mata pelajaran
                    ini.</p>
            @endforelse
        </div>
    </div>

    @if ($lastAccessed->isNotEmpty())
        <div class="p-6 mt-8 bg-white rounded-lg" wire:poll.5s>
            <h2 class="mb-6 text-xl font-bold text-gray-900">Materi Terakhir Diakses</h2>
            @foreach ($lastAccessed->take(1) as $material)
                <x-ui.student.materials.last-accessed-card :material="$material" />
            @endforeach
        </div>
    @endif
</div>
