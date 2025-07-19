@props([
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'model' => '',
    'icon' => '',
    'passwordToggle' => false,
])

<div class="mb-5">
    <div class="flex items-center border border-gray-700 rounded-md py-2 px-4 bg-gray-50 shadow-sm">
        @if ($icon)
            <span class="text-gray-500 mr-3">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <input type="{{ $type }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
            wire:model.live="{{ $model }}"
            {{ $attributes->merge(['class' => 'flex-grow bg-transparent outline-none text-gray-800 placeholder-gray-400 text-base']) }}
            {{ $attributes->has('required') ? 'required' : '' }} {{ $attributes->has('autofocus') ? 'autofocus' : '' }}>

        @if ($passwordToggle)
            <span class="text-gray-500 ml-3 cursor-pointer"
                onclick="togglePasswordVisibility('{{ $id }}', this)"><i class="fa fa-eye-slash"></i></span>
        @endif
    </div>
    @error($model)
        <span class="text-red-500 text-sm mt-1 block text-left pl-4">{{ $message }}</span>
    @enderror
</div>
