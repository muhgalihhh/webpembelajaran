@props(['label', 'name', 'wireModel', 'options', 'error' => '', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="mt-1">
        <select id="{{ $name }}" name="{{ $name }}" wire:model="{{ $wireModel }}"
            {{ $required ? 'required' : '' }}
            class="w-full px-3 py-2 border rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @if ($error) border-red-500 @endif">
            <option value="">Pilih salah satu</option>
            @foreach ($options as $key => $option)
                @if (is_object($option))
                    {{-- Digunakan untuk data dari database (Collection of Objects) --}}
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @else
                    {{-- Digunakan untuk data array biasa (key => value) --}}
                    <option value="{{ $key }}">{{ $option }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @if ($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
