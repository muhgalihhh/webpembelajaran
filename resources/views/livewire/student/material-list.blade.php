<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <x-ui.student.container title="Daftar Materi" :subject="$subject">
        {{-- @include('livewire.student.partials._header', ['subject' => $subject]) --}}
        <div class="pt-6 sm:pt-8">
            @include('livewire.student.partials._tabs', ['activeTab' => $activeTab])
        </div>

        <div class="p-6 rounded-lg bg-blue-50 min-h-96">
            @if ($activeTab === 'text')
                <div>
                    <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                        @forelse ($textMaterials as $material)
                            <x-ui.student.materials.card :material="$material" context="text"
                                wire:key="text-{{ $material->id }}" />
                        @empty
                            <p class="py-12 text-center text-gray-600 col-span-full">Belum ada materi teks/PDF untuk
                                mata
                                pelajaran ini.</p>
                        @endforelse
                    </div>

                    @if ($textMaterials->hasPages())
                        <div class="pt-8 mt-6 border-t border-blue-200">
                            {{ $textMaterials->links() }}
                        </div>
                    @endif
                </div>
            @endif
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
    </x-ui.student.container>
</div>
