@props([
    'label',
    'name',
    'wireModel',
    'options',
    'required' => false,
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

{{-- Wrapper utama tanpa margin atau lebar tetap --}}
<div {{ $attributes->except('class') }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="mt-1">
        {{-- PERUBAHAN: Menggunakan @error untuk menambahkan kelas border-red-500 secara dinamis --}}
        <select id="{{ $name }}" name="{{ $name }}" wire:model.live="{{ $wireModel }}"
            {{ $required ? 'required' : '' }}
            class="w-full px-3 py-2 border bg-blue-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error($name) border-red-500 @else border-gray-300 @enderror">
            <option value="">Pilih salah satu</option>
            @foreach ($options as $key => $option)
                @if (is_object($option))
                    <option value="{{ $option->{$optionValue} }}">{{ $option->{$optionLabel} }}</option>
                @else
                    <option value="{{ $key }}">{{ $option }}</option>
                @endif
            @endforeach
        </select>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
