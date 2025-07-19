<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @vite(['resources/css/app.css']);
    @livewireStyles
</head>

<body>
    <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-blue-600 border-t-transparent"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>
    <div class="bg-[#EBF3FF] text-gray-800">

        <x-ui.landing-nav />

        {{ $slot }}
    </div>
    @livewireScripts
    <x-ui.logout-confirmation />

    @livewire('auth.logout-handler')

    @vite(['resources/js/app.js'])
</body>

</html>
