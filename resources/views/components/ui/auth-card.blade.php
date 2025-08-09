<div class="flex items-center justify-center min-h-screen p-4 bg-gray-100">
    <div
        {{ $attributes->merge(['class' => 'relative bg-white p-8 rounded-lg shadow-xl  overflow-hidden border border-blue-700']) }}>
        {{-- Background Logo SD --}}
        <div class="absolute inset-0 z-0 flex items-center justify-center opacity-10">
            <img src="{{ asset('images/logo sd.png') }}" alt="SD Logo Background" class="object-contain w-full">
        </div>
        <div class="relative z-10">
            {{ $slot }}
        </div>
    </div>
</div>
