<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Media Pembelajaran Digital</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css'])
    @livewireStyles

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Transisi fade untuk halaman */
        [x-cloak] {
            display: none !important;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-[#EBF3FF] text-gray-800">
    <!-- Loading overlay untuk Livewire -->
    <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center">
        <div class="flex items-center p-6 space-x-3 bg-white rounded-lg">
            <div class="w-6 h-6 border-2 border-blue-600 rounded-full animate-spin border-t-transparent"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <!-- Main container dengan Alpine.js transisi -->
    <div class="h-screen-auto-overflow" x-data="{
        loaded: false,
        init() {
            // Delay sedikit untuk memastikan DOM siap
            setTimeout(() => {
                this.loaded = true;
            }, 100);
        }
    }" x-cloak>

        <!-- Header dengan transisi -->
        <header
            class="bg-[#4A90E2] text-white py-3 px-6 flex justify-between items-center rounded-b-lg shadow-md w-full  mx-auto fade-in"
            :class="{ 'show': loaded }">

            <x-ui.logo-nav />
            <div class="flex items-center space-x-3">
                {{-- Tombol ADMIN --}}
                <a href="{{ route('admin.login') }}" wire:navigate
                    class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold flex items-center space-x-1 hover:bg-gray-200 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-user-shield"></i>
                    <span>ADMIN</span>
                </a>

                <a href="{{ route('register') }}" wire:navigate
                    class="bg-white text-[#4A90E2] px-4 py-2 rounded-lg font-semibold border border-[#4A90E2] hover:bg-gray-200 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-user-plus"></i>
                    Daftar
                </a>
            </div>
        </header>
        <!-- Main section dengan transisi -->
        <main class="relative flex items-center justify-center flex-grow px-6 py-8 md:py-12 fade-in"
            :class="{ 'show': loaded }" x-transition:enter="transition ease-out duration-800 delay-200"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">


            <div class="bg-school-logo"></div>

            <div
                class="relative z-10 flex flex-col items-center justify-center w-full max-w-6xl lg:flex-row lg:items-center">
                <!-- Konten teks -->
                <div class="flex flex-col items-center text-center lg:items-start lg:text-left lg:w-1/2 lg:pr-8"
                    x-show="loaded" x-transition:enter="transition ease-out duration-1000 delay-400"
                    x-transition:enter-start="opacity-0 transform -translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0">

                    <h1 class="mb-4 text-2xl font-bold leading-tight text-gray-900 sm:text-3xl lg:text-4xl xl:text-5xl">
                        MASUK ATAU DAFTAR AKUN MEDIA PEMBELAJARAN DIGITAL SEKOLAH DASAR
                    </h1>
                    <p class="max-w-xl mb-8 text-base italic text-gray-600 sm:text-lg">
                        "Belajar hari ini adalah investasi untuk masa depan yang lebih cerah. Jadilah yang terbaik
                        versi
                        dirimu!"
                    </p>
                    {{-- Tombol Masuk Sekarang --}}
                    <a href="{{ route('login') }}"
                        class="bg-[#4A90E2] text-white px-8 py-3 rounded-xl text-lg font-semibold shadow-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-105"
                        wire:navigate>
                        Masuk Sekarang
                    </a>
                </div>

                <!-- Gambar siswa -->
                <div class="flex justify-center mt-8 lg:w-1/2 lg:mt-0" x-show="loaded"
                    x-transition:enter="transition ease-out duration-1000 delay-600"
                    x-transition:enter-start="opacity-0 transform translate-x-8 scale-95"
                    x-transition:enter-end="opacity-100 transform translate-x-0 scale-100">

                    <img src="{{ asset('images/Siswa.png') }}" alt="Student" class="student-image">
                </div>
            </div>
        </main>

        <!-- Feature cards section -->
        <section class="grid w-full grid-cols-1 gap-4 px-6 pb-6 mx-auto md:grid-cols-3 max-w-7xl" x-show="loaded"
            x-transition:enter="transition ease-out duration-900 delay-800"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">

            {{-- Kartu Materi Lengkap --}}
            <a href=""
                class="flex flex-col items-center p-4 text-center transition-all duration-300 transform bg-white shadow-md feature-card-link rounded-xl hover:scale-105 hover:shadow-lg"
                x-transition:enter="transition ease-out duration-600 delay-900"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">

                <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.042 0-2.042.153-3 .436v7.126c0 2.474 1.724 4.545 4 5.025a11.968 11.968 0 0 0 6-.992 11.968 11.968 0 0 0 6 .992c2.276-.48 4-2.551 4-5.025V4.186c-.958-.283-1.958-.436-3-.436a8.967 8.967 0 0 0-6 2.292Z" />
                    </svg>
                </div>
                <h3 class="mb-1 text-lg font-semibold">Materi Lengkap</h3>
                <p class="text-sm text-gray-600">Akses materi pembelajaran sesuai kurikulum dengan format yang mudah
                    dipahami</p>
            </a>

            {{-- Kartu Kuis Interaktif --}}
            <a href=""
                class="flex flex-col items-center p-4 text-center transition-all duration-300 transform bg-white shadow-md feature-card-link rounded-xl hover:scale-105 hover:shadow-lg"
                x-transition:enter="transition ease-out duration-600 delay-1000"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">

                <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.035-.259a3.375 3.375 0 0 0 2.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM10.5 17.25H2.25" />
                    </svg>
                </div>
                <h3 class="mb-1 text-lg font-semibold">Kuis Interaktif</h3>
                <p class="text-sm text-gray-600">Asah pemahamanmu dengan mengerjakan kuis dari guru, dan capai
                    peringkat
                    terbaik dengan Nilai tertinggi!</p>
            </a>

            {{-- Kartu Referensi Game Edukatif --}}
            <a href=""
                class="flex flex-col items-center p-4 text-center transition-all duration-300 transform bg-white shadow-md feature-card-link rounded-xl hover:scale-105 hover:shadow-lg"
                x-transition:enter="transition ease-out duration-600 delay-1100"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">

                <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0H15M21 3v6" />
                    </svg>
                </div>
                <h3 class="mb-1 text-lg font-semibold">Referensi Game Edukatif</h3>
                <p class="text-sm text-gray-600">Latih logika dan kreativitasmu lewat permainan seru yang penuh
                    tantangan edukatif!</p>
            </a>
        </section>

        <!-- Footer -->
        <footer class="w-full py-4 mx-auto text-xs font-bold text-center text-white bg-[#4A90E2] rounded-t-lg fade-in"
            :class="{ 'show': loaded }" x-transition:enter="transition ease-out duration-600 delay-1200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            &copy; 2025 MEDPEM-DIGITAL BY RAUMAT ALFAJR
        </footer>
    </div>

    @vite(['resources/js/app.js'])
    @livewireScripts

</body>

</html>
