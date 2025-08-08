@props(['game'])

@php
    // Palet warna berdasarkan mata pelajaran
    $subjectName = strtolower($game->subject->name ?? 'default');
    $palettes = [
        'ipa' => ['border' => 'border-yellow-500', 'bg' => 'bg-yellow-300', 'button' => 'bg-yellow-500'],
        'ilmu pengetahuan alam' => [
            'border' => 'border-yellow-500',
            'bg' => 'bg-yellow-300',
            'button' => 'bg-yellow-500',
        ],
        'pai' => ['border' => 'border-teal-500', 'bg' => 'bg-teal-300', 'button' => 'bg-teal-500'],
        'pendidikan agama islam' => ['border' => 'border-teal-500', 'bg' => 'bg-teal-300', 'button' => 'bg-teal-500'],
        'matematika' => ['border' => 'border-blue-500', 'bg' => 'bg-blue-300', 'button' => 'bg-blue-500'],
        'mtk' => ['border' => 'border-blue-500', 'bg' => 'bg-blue-300', 'button' => 'bg-blue-500'],
        'bahasa indonesia' => ['border' => 'border-red-500', 'bg' => 'bg-red-300', 'button' => 'bg-red-500'],
        'default' => ['border' => 'border-gray-500', 'bg' => 'bg-gray-300', 'button' => 'bg-gray-500'],
    ];
    $color = $palettes[$subjectName] ?? $palettes['default'];
@endphp

<div class="flex flex-col text-center border-4 {{ $color['border'] }} rounded-lg shadow-lg bg-white">
    <div class="p-4 bg-white rounded-t-md">
        <p class="font-bold text-gray-800 truncate">{{ $game->title }}</p>
    </div>
    <div class="flex flex-col items-center justify-center flex-grow p-4 {{ $color['bg'] }}">
        <p class="text-lg font-extrabold text-gray-900">{{ strtoupper($game->subject->name ?? 'N/A') }}</p>
        <p class="text-sm font-bold text-gray-700">KELAS {{ $game->class->class ?? 'N/A' }}</p>
    </div>
    <a href="{{ $game->game_url }}" target="_blank"
        class="block w-full py-3 font-bold text-white {{ $color['button'] }} rounded-b-sm">
        MULAI MAIN
    </a>
</div>
