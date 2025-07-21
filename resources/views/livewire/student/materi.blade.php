<div>
    <main class="container mx-auto mt-8 px-4">
        <h1 class="bg-green-500 text-center text-3xl font-extrabold text-white py-4 rounded-xl mb-8">
            LIHAT MATERI PEMBELAJARAN
        </h1>
        <h2 class="text-center text-2xl font-extrabold mb-8">PILIH MATA PELAJARAN YANG INGIN DIPELAJARI</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            <!-- Card Item -->
            @foreach ($subjects as $subject)
                <?php
                    // Array of colors to pick from
                    $colors = ['#FFB74D', '#64B5F6', '#BA68C8', '#FFEB3B', '#F48FB1'];
                    // Get a random color
                    $randomColor = $colors[array_rand($colors)];
                ?>
                <div class="bg-green-500 text-center rounded-lg shadow-lg hover:shadow-xl transition-shadow p-4 h-52">
                    <div class="relative bg-white p-6 rounded-lg h-30 flex flex-col justify-between" style="background-color: <?= $randomColor ?>;">
                      <h2 class="text-xl font-bold text-white break-words" style="text-stroke: 1px black;">
                          {{ $subject['name'] }}
                      </h2>
                    </div>
                    <a class="mt-4 inline-block px-6 py-2 bg-white text-black font-semibold rounded-xl shadow hover:bg-gray-200 transition duration-300">Lihat Materi</a>
                </div>
            @endforeach
        </div>
    </main>
</div>
