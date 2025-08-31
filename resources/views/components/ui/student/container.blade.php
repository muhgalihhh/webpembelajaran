{{-- resources/views/components/card-container.blade.php --}}
@props([
    'title' => 'Container Title',
    'header_color' => 'from-cyan-400 to-teal-500',
    'icon' => 'fa-solid fa-box-open',
])

<div class="relative w-full">
    <div class="relative z-10 px-4 sm:px-6">
        <div
            class="border inline-flex items-center space-x-3 rounded-xl shadow-lg bg-gradient-to-r {{ $header_color }} px-4 py-3 backdrop-blur-sm">
            <div class="flex-shrink-0 p-2 border rounded-lg bg-black/30 backdrop-blur-sm border-white/20">
                <i class="{{ $icon }} text-white text-lg sm:text-xl"></i>
            </div>
            <!-- Title -->
            <h2 class="text-sm font-bold tracking-wide text-black uppercase sm:text-base lg:text-lg whitespace-nowrap">
                {{ $title }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg mt-[-12px] pt-8 overflow-hidden mx-2 sm:mx-0 border">
        <!-- Content Area -->
        <div class="px-4 pt-2 pb-6 sm:px-6">
            {{ $slot }}
        </div>
    </div>
</div>
