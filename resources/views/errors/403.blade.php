@extends('layouts.error')

@section('title', '403 | Dilarang Masuk!')

@section('content')
    <div x-data="{ show: false, animate: false }" x-init="() => {
        setTimeout(() => show = true, 100);
        setTimeout(() => animate = true, 600);
    }" x-show="show" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        class="w-full max-w-md p-8 mx-auto text-center bg-white shadow-xl rounded-2xl">

        {{-- Ikon Tangan --}}
        <div class="text-red-400 text-7xl">
            <i class="fa-solid fa-hand-paper"></i>
        </div>

        <h1 class="inline-block font-black text-red-600 text-8xl" :class="{ 'animate-wiggle': animate }">403</h1>
        <h2 class="mt-4 text-3xl font-semibold text-gray-800">Eits, Mau ke Mana?</h2>
        <p class="px-4 mt-2 text-gray-600">Halaman ini adalah area terlarang untukmu. Tidak ada jalan masuk!</p>
        <div class="mt-8">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-6 py-3 font-semibold text-white transition-all duration-300 transform bg-red-600 rounded-lg shadow-md hover:bg-red-700 hover:scale-105">
                <i class="mr-2 fas fa-arrow-left"></i>
                Putar Balik
            </a>
        </div>
    </div>
@endsection
