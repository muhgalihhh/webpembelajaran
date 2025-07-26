@props(['label', 'name', 'wireModel', 'placeholder' => '', 'rows' => 3, 'error' => ''])

{{-- Wrapper utama tanpa margin atau lebar tetap --}}
<div {{ $attributes->except('class') }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1">
        <textarea id="{{ $name }}" name="{{ $name }}" wire:model="{{ $wireModel }}" rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            class="w-full px-3 py-2 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @if ($error) border-red-500 @endif"></textarea>
    </div>
    @if ($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
