<div>
    {{-- Ini mengisi slot 'pageHeader' di layout utama --}}
    <x-slot:pageHeader>
        <div class="flex items-center">
            {{-- Tombol Hamburger untuk Mobile --}}
            <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
                <i class="text-xl fa-solid fa-bars"></i>
            </button>
            {{-- Judul Halaman --}}
            <h2 class="text-2xl font-bold text-gray-800">
                Dashboard
            </h2>
        </div>
    </x-slot:pageHeader>

    {{-- Konten metrik --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Students</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
            </div>
            <div class="p-3 text-blue-600 bg-blue-100 rounded-full">
                <i class="fas fa-user-graduate fa-2x"></i>
            </div>
        </div>
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Teachers</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalTeachers }}</p>
            </div>
            <div class="p-3 text-green-600 bg-green-100 rounded-full">
                <i class="fas fa-chalkboard-teacher fa-2x"></i>
            </div>
        </div>
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Classes</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalClasses }}</p>
            </div>
            <div class="p-3 text-yellow-600 bg-yellow-100 rounded-full">
                <i class="fas fa-school fa-2x"></i>
            </div>
        </div>
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Subjects</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalSubjects }}</p>
            </div>
            <div class="p-3 text-purple-600 bg-purple-100 rounded-full">
                <i class="fas fa-book fa-2x"></i>
            </div>
        </div>
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Quizzes</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalQuizzes }}</p>
            </div>
            <div class="p-3 text-red-600 bg-red-100 rounded-full">
                <i class="fas fa-question-circle fa-2x"></i>
            </div>
        </div>
        <div class="flex items-center justify-between p-6 bg-white border shadow-md">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Total Tasks</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
            </div>
            <div class="p-3 text-teal-600 bg-teal-100 rounded-full">
                <i class="fas fa-tasks fa-2x"></i>
            </div>
        </div>
    </div>
</div>
