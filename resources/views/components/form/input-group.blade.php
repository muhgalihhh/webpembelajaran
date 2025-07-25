@props([
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'wireModel' => '',
    'label' => '',
    'icon' => '',
    'passwordToggle' => false,
])

<div class="mb-5">
    @if ($label)
        <label for="{{ $id }}" class="mr-3 text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <div class="flex items-center px-4 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-50">
        @if ($icon)
            <span class="mr-3 text-gray-500">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <input type="{{ $type }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
            wire:model.live="{{ $wireModel }}"
            {{ $attributes->merge(['class' => 'flex-grow bg-transparent outline-none text-gray-800 placeholder-gray-400 text-base']) }}
            {{ $attributes->has('required') ? 'required' : '' }} {{ $attributes->has('autofocus') ? 'autofocus' : '' }}>

        @if ($passwordToggle)
            <span class="ml-3 text-gray-500 cursor-pointer"
                onclick="togglePasswordVisibility('{{ $id }}', this)"><i class="fa fa-eye-slash"></i></span>
        @endif
    </div>
    @error($wireModel)
        <span class="block pl-4 mt-1 text-sm text-left text-red-500">{{ $message }}</span>
    @enderror
</div>
