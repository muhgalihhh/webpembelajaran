{{-- resources/views/components/alert.blade.php --}}

@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'bg-green-500 border-green-700',
        'error' => 'bg-red-500 border-red-700',
        'warning' => 'bg-yellow-500 border-yellow-700',
    ];
@endphp

{{--
  x-data: Inisialisasi state Alpine.js. 'show' bernilai true.
  x-show: Tampilkan elemen ini jika 'show' adalah true.
  x-init: Jalankan kode saat komponen dimuat. Di sini, kita set timer 4 detik.
  x-transition: Tambahkan animasi fade-in dan fade-out yang halus.
--}}
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed bottom-5 right-5 w-auto max-w-xs px-4 py-3 rounded-lg shadow-lg text-white border-l-4 {{ $colors[$type] }} z-[99999]"
    role="alert">

    <p class="font-bold">{{ ucfirst($type) }}</p>
    <p class="text-sm">{{ $message }}</p>
</div>
