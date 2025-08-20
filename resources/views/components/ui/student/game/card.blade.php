@props(['game'])

@php
    $subjectName = strtolower($game->subject->name ?? 'default');
    $palettes = [
        'ipa' => [
            'bg' => 'bg-gradient-to-br from-amber-100 to-yellow-200',
            'border' => 'border-yellow-300',
            'text' => 'text-yellow-800',
        ],
        'ilmu pengetahuan alam' => [
            'bg' => 'bg-gradient-to-br from-amber-100 to-yellow-200',
            'border' => 'border-yellow-300',
            'text' => 'text-yellow-800',
        ],
        'pai' => [
            'bg' => 'bg-gradient-to-br from-emerald-100 to-green-200',
            'border' => 'border-green-300',
            'text' => 'text-green-800',
        ],
        'pendidikan agama islam' => [
            'bg' => 'bg-gradient-to-br from-emerald-100 to-green-200',
            'border' => 'border-green-300',
            'text' => 'text-green-800',
        ],
        'matematika' => [
            'bg' => 'bg-gradient-to-br from-sky-100 to-blue-200',
            'border' => 'border-blue-300',
            'text' => 'text-blue-800',
        ],
        'mtk' => [
            'bg' => 'bg-gradient-to-br from-sky-100 to-blue-200',
            'border' => 'border-blue-300',
            'text' => 'text-blue-800',
        ],
        'bahasa indonesia' => [
            'bg' => 'bg-gradient-to-br from-rose-100 to-red-200',
            'border' => 'border-red-300',
            'text' => 'text-red-800',
        ],
        'default' => [
            'bg' => 'bg-gradient-to-br from-gray-100 to-gray-200',
            'border' => 'border-gray-300',
            'text' => 'text-gray-800',
        ],
    ];
    $color = $palettes[$subjectName] ?? $palettes['default'];
@endphp

<div
    class="flex flex-col h-full overflow-hidden transition-all duration-300 transform border shadow-lg rounded-2xl hover:shadow-[6px_6px_0px_rgba(0,0,0,0.2)] hover:-translate-y-2 {{ $color['bg'] }}">

    <div class="px-3 py-2 text-center">
        <p class="text-sm font-bold {{ $color['text'] }} truncate" title="{{ $game->title }}">
            {{ $game->title }}
        </p>
    </div>

    <div class="flex flex-col items-center justify-center flex-grow p-4">

        <p class="text-xl font-black text-center {{ $color['text'] }} md:text-2xl">
            {{ strtoupper($game->subject->name ?? 'N/A') }}
        </p>
        <p class="text-lg font-bold text-center {{ $color['text'] }}">KELAS {{ $game->class->class ?? 'N/A' }}</p>
    </div>

    <div class="p-2">
        {{ $slot }}
    </div>
</div>
