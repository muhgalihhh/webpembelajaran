<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4 ">
    <div
        {{ $attributes->merge(['class' => 'relative bg-white p-8 rounded-lg shadow-xl w-full max-w-sm overflow-hidden border border-blue-700']) }}>
        {{-- Background Logo SD --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-10 z-0">
            <img src="{{ asset('images/logo sd.png') }}" alt="SD Logo Background" class="w-full object-contain">
        </div>
        <div class="relative z-10">
            {{ $slot }}
        </div>
    </div>
</div>
