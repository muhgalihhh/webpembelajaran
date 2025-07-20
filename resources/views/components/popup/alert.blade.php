@props(['name' => 'manual-popup'])

<div x-data="{
    show: false,
    title: '',
    message: '',
    buttonLabel: 'OK',
    redirectUrl: null,
    icon: 'check' // 'check', 'info', 'warning', 'error'
}"
    x-on:show-manual-popup.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        buttonLabel = $event.detail.buttonLabel;
        redirectUrl = $event.detail.redirectUrl;
        icon = $event.detail.icon || 'check';
        document.body.classList.add('overflow-y-hidden');
    "
    x-on:keydown.escape.window="show = false" x-show="show" x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none; background-color: rgba(0, 0, 0, 0.6);">
    {{-- Konten Popup --}}
    <div x-show="show" x-trap.inert.noscroll="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        class="w-full max-w-md overflow-hidden rounded-lg shadow-xl" style="background-color: #38A2AC;"
        {{-- Warna Teal sesuai gambar --}}>
        <div class="p-8 text-center text-white">
            {{-- Icon --}}
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full"
                :class="{
                    'bg-blue-600': icon === 'check' || icon === 'success',
                    'bg-yellow-500': icon === 'warning',
                    'bg-red-500': icon === 'error',
                }">
                <i class="text-3xl fas"
                    :class="{
                        'fa-check': icon === 'check' || icon === 'success',
                        'fa-exclamation-triangle': icon === 'warning',
                        'fa-times-circle': icon === 'error',
                        'fa-info-circle': icon === 'info',
                    }"></i>
            </div>

            {{-- Judul --}}
            <h3 class="text-2xl font-bold" x-text="title"></h3>

            {{-- Pesan --}}
            <p class="mt-2" x-text="message"></p>

            {{-- Tombol Aksi --}}
            <div class="mt-8">
                <button
                    @click="() => {
                        show = false;
                        document.body.classList.remove('overflow-y-hidden');
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        }
                    }"
                    class="inline-block px-10 py-3 font-semibold text-gray-800 transition-colors bg-white border-2 border-gray-700 rounded-lg shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-teal-500"
                    x-text="buttonLabel"></button>
            </div>
        </div>
    </div>
</div>
