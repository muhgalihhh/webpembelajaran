<div class="flex items-center">
    {{-- Logo (Selalu Terlihat) --}}
    <a href="{{ route('admin.index') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo sd.png') }}" alt="Logo" class="h-10">
    </a>


    <div class="hidden ml-3 sm:flex sm:flex-col">
        {{-- Judul --}}
        <h3 class="px-2 py-1 text-lg font-bold text-[#4A90E2] bg-white rounded-sm">
            Media Pembelajaran Digital
        </h3>

        {{-- Sub-judul --}}
        <span class="text-sm text-white">
            MEDPEM-DIGITAL ||
            <span class="text-xs text-orange">by Raumath Alfajr</span>
        </span>
    </div>
</div>
