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

    @vite(['resources/css/app.css'])
    @livewireStyles
</head>

<body x-data="{ pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 150)" x-show="pageLoaded" x-transition.opacity.duration.500ms>

    @if (session()->has('success'))
        <x-ui.alert-popup type="success" :message="session('success')" />
    @endif

    @if (session()->has('error'))
        <x-ui.alert-popup type="error" :message="session('error')" />
    @endif


    <!-- Loading Overlay -->
    <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center"
        x-transition.opacity.duration.300ms>
        <div class="flex items-center p-6 space-x-3 bg-white rounded-lg" x-transition.scale.80.opacity.duration.400ms>
            <div class="w-6 h-6 border-2 border-blue-600 rounded-full animate-spin border-t-transparent"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <!-- Main Content -->
    <div x-show="pageLoaded" x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        {{ $slot }}
    </div>

    @livewireScripts
    <x-form.password-toggle />

    @vite(['resources/js/app.js'])
</body>

</html>
