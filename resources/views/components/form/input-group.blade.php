@props([
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'wireModel' => '',
    'label' => '',
    'icon' => '',
    'passwordToggle' => false,
])

{{-- Wrapper utama tanpa margin atau lebar tetap --}}
<div {{ $attributes->except('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <div x-data="{ show: false }"
        class="flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500 @if ($label) mt-1 @endif">
        @if ($icon)
            <span class="mr-2 text-gray-500">
                <i class="{{ $icon }}"></i>
            </span>
        @endif

        <input :type="show ? 'text' : '{{ $type }}'" id="{{ $id }}" placeholder="{{ $placeholder }}"
            wire:model.live.debounce.300ms="{{ $wireModel }}"
            class="flex-grow w-full text-base text-gray-800 placeholder-gray-400 bg-transparent outline-none">

        @if ($passwordToggle)
            <span class="ml-3 text-gray-500 cursor-pointer" @click="show = !show">
                <i class="fa" :class="{ 'fa-eye-slash': !show, 'fa-eye': show }"></i>
            </span>
        @endif
    </div>

    @error($wireModel)
        <span class="block mt-2 text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
