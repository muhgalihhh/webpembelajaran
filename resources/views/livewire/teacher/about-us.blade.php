<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Tentang Aplikasi</h2>
    </x-slot:pageHeader>

    <div class="p-6 mx-auto bg-white border rounded-lg shadow-md">
        <div class="text-center">
            <i class="mb-4 text-5xl text-blue-500 fa-solid fa-rocket"></i>
            <h1 class="text-3xl font-bold text-gray-800">Media Pembelajaran Digital</h1>
            <p class="mt-2 text-lg text-gray-600">Membawa Semangat Belajar ke Era Digital</p>
        </div>

        <div class="my-8 border-t border-gray-200"></div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Misi Kami</h2>
                <p class="mt-2 text-gray-600">
                    Aplikasi ini dirancang khusus untuk memberikan pengalaman belajar yang modern, interaktif, dan
                    menyenangkan bagi siswa. Kami percaya bahwa dengan memanfaatkan teknologi, proses belajar
                    mengajar dapat menjadi lebih efektif dan menarik, baik bagi siswa maupun guru.
                </p>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">Untuk Para Guru</h2>
                <p class="mt-2 text-gray-600">
                    Kami menyediakan berbagai fitur untuk memudahkan Anda dalam mengelola materi, memberikan tugas dan
                    kuis, serta memantau perkembangan setiap siswa secara efisien.
                </p>
            </div>
        </div>

        <div class="my-8">
            <h3 class="text-2xl font-semibold text-center text-gray-700">Fitur Unggulan</h3>
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="p-4 text-center border rounded-lg bg-gray-50">
                    <i class="text-3xl text-indigo-500 fa-solid fa-book-open"></i>
                    <p class="mt-2 font-semibold">Manajemen Materi</p>
                </div>
                <div class="p-4 text-center border rounded-lg bg-gray-50">
                    <i class="text-3xl text-green-500 fa-solid fa-pencil-alt"></i>
                    <p class="mt-2 font-semibold">Manajemen Kuis</p>
                </div>
                <div class="p-4 text-center border rounded-lg bg-gray-50">
                    <i class="text-3xl text-red-500 fa-solid fa-tasks"></i>
                    <p class="mt-2 font-semibold">Manajemen Tugas</p>
                </div>
                <div class="p-4 text-center border rounded-lg bg-gray-50">
                    <i class="text-3xl text-yellow-500 fa-solid fa-trophy"></i>
                    <p class="mt-2 font-semibold">Peringkat Siswa</p>
                </div>
            </div>
        </div>
    </div>
</div>
