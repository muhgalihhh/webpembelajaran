<div class="h-screen overflow-auto">
    {{-- Bagian utama konten --}}
    <main class="relative flex items-center justify-center flex-grow px-6 py-8 md:py-12">
        {{-- Div untuk logo latar belakang. Penting: posisinya di sini agar bisa diatur z-index di belakang konten. --}}
        <div class="bg-school-logo"></div>

        <div
            class="relative z-10 flex flex-col items-center justify-center w-full max-w-6xl lg:flex-row lg:items-center">
            <div class="flex flex-col items-center text-center lg:items-start lg:text-left lg:w-1/2 lg:pr-8">
                <h1 class="text-2xl font-bold leading-tight text-gray-900  sm:text-3xl lg:text-4xl xl:text-5xl">
                    HALOO DIK {{ Auth::user()->name }}
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
                    Masuk Sekarang
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

        {{-- Kartu Kuis Interaktif --}}
        <a href=""
            class="flex flex-col items-center p-4 text-center bg-white shadow-md feature-card-link rounded-xl">
            <div class="bg-[#EBF3FF] rounded-full p-3 mb-3 icon-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.035-.259a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM10.5 17.25H2.25" />
                </svg>
            </div>
            <h3 class="mb-1 text-lg font-semibold">Kuis Interaktif</h3>
            <p class="text-sm text-gray-600">Asah pemahamanmu dengan mengerjakan kuis dari guru, dan capai
                peringkat
                terbaik dengan Nilai tertinggi!</p>
        </a>

        {{-- Kartu Referensi Game Edukatif --}}
        <a href=""
            class="flex flex-col items-center p-4 text-center bg-white shadow-md feature-card-link rounded-xl">
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


</div>
