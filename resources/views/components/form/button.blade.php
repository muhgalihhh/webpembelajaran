@props(['type' => 'submit', 'label' => '', 'wireClick' => ''])

<button type="{{ $type }}" @if ($wireClick) wire:click="{{ $wireClick }}" @endif
    {{ $attributes->merge(['class' => ' hover:bg-blue-700 text-white font-semibold py-2 px-1 rounded-md transition duration-300 ease-in-out text-base']) }}>
    {{ $label ?? $slot }}
</button>
