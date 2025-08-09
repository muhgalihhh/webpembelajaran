@extends('layouts.error')

@section('title', '500 | Servernya Pusing')

@section('content')
    <div x-data="{ show: false, animate: false }" x-init="() => {
        setTimeout(() => show = true, 100);
        setTimeout(() => animate = true, 600);
    }" x-show="show" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        class="w-full max-w-md p-8 mx-auto text-center bg-white shadow-xl rounded-2xl">

        {{-- Ikon Robot --}}
        <div class="text-orange-400 text-7xl">
            <i class="fa-solid fa-robot"></i>
        </div>

        <h1 class="inline-block font-black text-orange-600 text-8xl" :class="{ 'animate-wiggle': animate }">500</h1>
        <h2 class="mt-4 text-3xl font-semibold text-gray-800">Aduh, Ada Korsleting!</h2>
        <p class="px-4 mt-2 text-gray-600">Robot-robot kami di server sepertinya sedang kebingungan. Kami akan perbaiki!</p>
        <div class="mt-8">
            <a href="{{ url('/login') }}"
                class="inline-flex items-center px-6 py-3 font-semibold text-white transition-all duration-300 transform bg-orange-600 rounded-lg shadow-md hover:bg-orange-700 hover:scale-105">
                <i class="mr-2 fas fa-home"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
