<div class="min-h-screen overflow-auto bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center; sm:background-size: 30rem;">

    <div class="container flex flex-col items-center justify-center px-4 py-12 mx-auto text-center sm:px-6 sm:py-16"
        x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">

        {{-- Header Halaman --}}
        <div class="w-full p-4 mb-12 bg-white border shadow-2xl md:w-2/3 lg:w-2/3 rounded-xl sm:p-6" x-show="isLoaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0">

            <h1 class="text-2xl font-extrabold tracking-wider text-gray-800 uppercase sm:text-4xl">
                Halaman Belajar Siswa Kelas {{ $this->studentClass?->class ?? '' }}
            </h1>
            <p class="mt-2 text-base text-gray-600 sm:text-lg">
                Yuk, jelajahi dunia belajar yang seru! Mulai dari materi, kuis, hingga game edukatif!
            </p>
        </div>

        {{-- Grid Responsif: 1 kolom di mobile, 2 di tablet, 4 di desktop --}}
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4" x-show="isLoaded"
            x-transition:enter="transition ease-out duration-700 delay-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0">

            <x-ui.student.feature-card title="Materi Pembelajaran" icon="fa-solid fa-book-open"
                link="{{ route('student.subjects') }}" linkText="Lihat Materi Pembelajaran" headerColor="bg-blue-500"
                bodyColor="bg-yellow-300" linkColor="bg-purple-600" />

            <x-ui.student.feature-card title="Mengerjakan Kuis" icon="fa-solid fa-file-pen"
                link="{{ route('student.quizzes') }}" linkText="Kerjakan Kuis / Latihan Soal"
                headerColor="bg-yellow-500" bodyColor="bg-green-300" linkColor="bg-green-600" />

            <x-ui.student.feature-card title="Bermain Game Edukatif" icon="fa-solid fa-gamepad"
                link="{{ route('student.games') }}" linkText="Main Game Edukatif" headerColor="bg-teal-500"
                bodyColor="bg-cyan-200" linkColor="bg-cyan-600" />

            <x-ui.student.feature-card title="Mengerjakan Tugas" icon="fa-solid fa-clipboard-list"
                link="{{ route('student.tasks') }}" linkText="Mengerjakan Tugas" headerColor="bg-orange-500"
                bodyColor="bg-red-300" linkColor="bg-red-600" />

        </div>

    </div>
</div>
