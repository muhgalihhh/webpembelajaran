<div class="min-h-screen overflow-auto bg-gradient-to-br from-sky-50 to-sky-200"
    style="background-image: url('/images/transparent bg.png'); background-size: 25rem; background-position: center;">

    <div class="container flex flex-col items-center justify-center px-4 py-12 mx-auto text-center sm:px-6 sm:py-16"
        x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">

        {{-- Header Halaman --}}
        <div class="w-full p-6 mb-12 bg-white border shadow-2xl md:w-2/3 lg:w-2/3 rounded-2xl sm:p-8" 
            x-show="isLoaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0">

            <h1 class="text-2xl font-extrabold tracking-wide text-sky-900 uppercase sm:text-4xl">
                Halaman Belajar Siswa Kelas {{ $this->studentClass?->class ?? '' }}
            </h1>
            <p class="mt-3 text-base text-sky-800 sm:text-lg">
                Yuk, jelajahi dunia belajar yang seru! Mulai dari materi, kuis, hingga game edukatif!
            </p>
        </div>

        {{-- Grid Responsif --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 w-full max-w-7xl"
            x-show="isLoaded"
            x-transition:enter="transition ease-out duration-700 delay-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0">

            <x-ui.student.feature-card title="Materi Pembelajaran" icon="fa-solid fa-book-open"
                link="{{ route('student.subjects') }}" linkText="Lihat Materi"
                headerColor="bg-sky-400" bodyColor="bg-sky-200" linkColor="bg-sky-700" class="h-full" />

            <x-ui.student.feature-card title="Mengerjakan Kuis" icon="fa-solid fa-file-pen"
                link="{{ route('student.quizzes') }}" linkText="Kerjakan Kuis"
                headerColor="bg-indigo-400" bodyColor="bg-indigo-200" linkColor="bg-indigo-700" class="h-full" />

            <x-ui.student.feature-card title="Referensi Game Edukatif" icon="fa-solid fa-gamepad"
                link="{{ route('student.games') }}" linkText="Main Game"
                headerColor="bg-cyan-400" bodyColor="bg-cyan-200" linkColor="bg-cyan-700" class="h-full" />

            <x-ui.student.feature-card title="Mengerjakan Tugas" icon="fa-solid fa-clipboard-list"
                link="{{ route('student.tasks') }}" linkText="Kerjakan Tugas"
                headerColor="bg-sky-400" bodyColor="bg-sky-200" linkColor="bg-sky-700" class="h-full" />

        </div>

    </div>
</div>
