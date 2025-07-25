@props(['label', 'name', 'wireModel', 'options', 'error' => ''])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1">
        <select id="{{ $name }}" name="{{ $name }}" wire:model="{{ $wireModel }}"
            class="py-3 px-2 w-full border rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @if ($error) border-red-500 @endif">
            <option value="">Pilih salah satu</option>
            @foreach ($options as $option)
                <option value="{{ $option->id }}">{{ $option->name }}</option>
            @endforeach
        </select>
    </div>
    @if ($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
