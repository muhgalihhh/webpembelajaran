<div class="min-h-screen px-4 py-10 bg-blue-100"
    style="background-image: url('/images/transparent bg.png'); background-size: 30rem; background-position: center;"
    x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true }, 50)">
    <div class="container mx-auto text-center" x-show="isLoaded" x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

        <!-- Header -->
        <div class="flex flex-col items-center justify-center mb-6">
            <h1
                class="inline-flex items-center gap-2 px-4 py-2 text-3xl font-bold text-white bg-green-500 border-2 border-green-500 rounded-lg shadow sm:text-4xl">
                <i class="fa-solid fa-book-open"></i>
                LIHAT MATERI PEMBELAJARAN
            </h1>
            <p class="px-3 py-2 mt-4 text-xl font-bold text-gray-800 bg-white shadow-md sm:text-2xl w-fit rounded-xl">
                PILIH MATA
                PELAJARAN
                YANG
                INGIN DIPELAJARI</p>
        </div>

        <!-- Grid Cards -->
        @if ($this->subjects->isNotEmpty())
            <div class="grid grid-cols-2 gap-6 mt-8 sm:grid-cols-3 lg:grid-cols-5">

                @php
                    // Array warna untuk membuat kartu lebih menarik
                    $colors = [
                        ['bg' => 'bg-yellow-400', 'border' => 'border-blue-500'],
                        ['bg' => 'bg-blue-400', 'border' => 'border-orange-500'],
                        ['bg' => 'bg-orange-400', 'border' => 'border-green-500'],
                        ['bg' => 'bg-green-400', 'border' => 'border-teal-500'],
                        ['bg' => 'bg-teal-400', 'border' => 'border-indigo-500'],
                        ['bg' => 'bg-indigo-400', 'border' => 'border-pink-500'],
                        ['bg' => 'bg-pink-400', 'border' => 'border-red-500'],
                    ];
                @endphp

                @foreach ($this->subjects as $index => $subject)
                    @php
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <x-ui.student.subject-card :title="$subject->name" :link="'#'" buttonText="Lihat Materi"
                        :bgColor="$color['bg']" :borderColor="$color['border']" />
                @endforeach
            </div>
        @else
            <div class="p-8 mt-8 text-center text-gray-500 bg-white rounded-lg shadow-md">
                <p class="text-xl font-semibold">Oops!</p>
                <p>Belum ada materi pembelajaran yang tersedia untuk kelas Anda saat ini.</p>
            </div>
        @endif

    </div>
</div>
