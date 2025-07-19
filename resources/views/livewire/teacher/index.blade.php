<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Media Pembelajaran Digital</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom CSS minimal untuk penyesuaian presisi */
        body {
            font-family: 'Figtree', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Ini penting untuk menjaga konten di dalam viewport tanpa scroll */
        .h-screen-auto-overflow {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Background image untuk logo sekolah SD */
        .bg-school-logo {
            background-image: url('{{ asset('images/logo sd.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 800px;
            /* Ukuran default untuk desktop */
            opacity: 0.2;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Penempatan gambar siswa - responsive */
        .student-image {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
        }

        /* Override untuk memastikan warna icon */
        /* Jika menggunakan Heroicons, ini bisa dihapus dan pakai kelas Tailwind langsung */
        .icon-blue svg {
            color: #4A90E2;
        }

        /* Pastikan elemen tautan kartu juga memiliki efek hover */
        .feature-card-link {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .feature-card-link:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .bg-school-logo {
                background-size: 650px;
            }

            .student-image {
                max-height: 300px;
            }
        }

        @media (max-width: 480px) {
            .bg-school-logo {
                background-size: 350px;
            }
        }
    </style>
</head>

<body class="bg-[#EBF3FF] text-gray-800">
    <div class="h-screen-auto-overflow">
        <main class="relative flex items-center justify-center flex-grow px-6 py-8 md:py-12">
            {{-- Div untuk logo latar belakang. Penting: posisinya di sini agar bisa diatur z-index di belakang konten. --}}
            <div class="bg-school-logo"></div>

            <div
                class="relative z-10 flex flex-col items-center justify-center w-full max-w-6xl lg:flex-row lg:items-center">
                <div class="flex flex-col items-center text-center lg:items-start lg:text-left lg:w-1/2 lg:pr-8">
                    <h1 class=" text-2xl font-bold leading-tight text-gray-900 sm:text-3xl lg:text-4xl xl:text-5xl">
                        HALOO IBU {{ Auth::user()->name }}
                    </h1>
                    <h1 class="mb-4 text-2xl font-bold leading-tight text-gray-900 sm:text-3xl lg:text-4xl xl:text-5xl">
                        SELAMAT DATANG DI MEDIA PEMBELAJARAN DIGITAL SEKOLAH DASAR || KELAS VI
                    </h1>
                    <p class="max-w-xl mb-8 text-base italic text-gray-600 sm:text-lg">
                        "Belajar hari ini adalah investasi untuk masa depan yang lebih cerah. Jadilah yang terbaik versi
                        dirimu!"
                    </p>
                    {{-- Tombol Masuk Sekarang --}}
                    <a href="{{ route('login') }}"
                        class="bg-[#4A90E2] text-white px-8 py-3 rounded-xl text-lg font-semibold shadow-lg hover:bg-blue-600 transition-colors duration-200"
                        wire:navigate>
                        Cek Aktivitas Siswa
                    </a>
                </div>

                <div class="flex justify-center mt-8 lg:w-1/2 lg:mt-0">
                    <img src="{{ asset('images/Siswa.png') }}" alt="Student" class="student-image">
                </div>
            </div>
        </main>

        <section class="grid w-full grid-cols-1 gap-4 px-6 pb-6 mx-auto md:grid-cols-3 max-w-7xl">
            {{-- Kartu Materi Lengkap --}}
            <a href=""
                class="flex flex-col items-center p-4 text-center bg-white shadow-md feature-card-link rounded-xl">
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


            <a href=""
                class="flex flex-col items-center p-4 text-center bg-white shadow-md feature-card-link rounded-xl">
                <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                    <i class="fas fa-chalkboard-teacher text-blue-500 w-6 h-6"></i>
                </div>
                <h3 class="mb-1 text-lg font-semibold">Upload Materi Mapel</h3>
                <p class="text-sm text-gray-600">Upload Materi Pembelajaran sesuai dengan kurikulum dengan format yang
                    mudah dipahami (pdf)</p>
            </a>


            <a href=""
                class="flex flex-col items-center p-4 text-center bg-white shadow-md feature-card-link rounded-xl">
                <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                    <i class="fas fa-chalkboard-teacher text-blue-500 w-6 h-6"></i>
                </div>
                <h3 class="mb-1 text-lg font-semibold">Upload Kuis/Soal Latihan</h3>
                <p class="text-sm text-gray-600">Latih logika dan kreativitasmu lewat permainan seru yang penuh
                    tantangan edukatif!</p>
            </a>
        </section>

        <footer
            class="w-full py-4 mx-auto text-xs font-bold text-center text-white max-w-7xl bg-[#4A90E2] rounded-t-lg">
            &copy; 2025 MEDPEM-DIGITAL BY RAUMAT ALFAJR
        </footer>
    </div>
</body>

</html>
