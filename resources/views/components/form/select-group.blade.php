@props([
    'label',
    'name',
    'wireModel',
    'options',
    'error' => '',
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
        <select id="{{ $name }}" name="{{ $name }}" wire:model.live="{{ $wireModel }}"
            {{ $required ? 'required' : '' }}
            class="w-full px-3 py-2 border border-gray-300 bg-blue-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @if ($error) border-red-500 @endif">
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
    @if ($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
