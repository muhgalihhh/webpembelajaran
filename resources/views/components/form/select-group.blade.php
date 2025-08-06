@props([
    'label',
    'name',
    'wireModel',
    'options',
    'required' => false,
    'placeholder' => 'Pilih salah satu',
    'optionLabel' => null,
])

<div x-data="{
    open: false,
    search: '',
    value: @entangle($wireModel).live, // Tambahkan .live untuk real-time sync
    selectedLabel: '',

    // Mengambil daftar opsi dari prop
    options: {{ json_encode($options) }},

    // Logika untuk memfilter opsi berdasarkan pencarian
    get filteredOptions() {
        if (this.search === '') {
            return this.options;
        }
        const searchLower = this.search.toLowerCase();

        // Handle jika options berupa array of objects (untuk optionLabel)
        if (typeof Object.values(this.options)[0] === 'object') {
            return Object.fromEntries(
                Object.entries(this.options).filter(([key, item]) => {
                    const label = '{{ $optionLabel }}' ? item['{{ $optionLabel }}'] : item.name || item.label;
                    return label.toLowerCase().includes(searchLower);
                })
            );
        } else {
            // Handle jika options berupa key-value pairs biasa
            return Object.fromEntries(
                Object.entries(this.options).filter(([key, label]) =>
                    label.toLowerCase().includes(searchLower)
                )
            );
        }
    },

    // Fungsi untuk memilih sebuah opsi
    selectOption(key, label) {
        this.value = key;
        this.selectedLabel = label;
        this.open = false;
        this.search = ''; // Reset search setelah memilih
    },

    // Fungsi untuk mendapatkan label dari value
    getLabelFromValue(val) {
        if (!val || !this.options[val]) {
            return '{{ $placeholder }}';
        }

        const option = this.options[val];
        if (typeof option === 'object') {
            return '{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label || val);
        }
        return option;
    },

    // Inisialisasi untuk menampilkan label yang sudah ada saat edit
    init() {
        // Tetapkan label awal jika ada nilai
        this.selectedLabel = this.getLabelFromValue(this.value);

        // Pantau perubahan dari Livewire untuk memperbarui label
        this.$watch('value', (newValue) => {
            this.selectedLabel = this.getLabelFromValue(newValue);
        });

        // Update options jika ada perubahan dari parent component
        this.$watch('options', () => {
            this.selectedLabel = this.getLabelFromValue(this.value);
        });
    }
}" x-init="init()" class="relative" @click.outside="open = false">
    {{-- Label --}}
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Tombol Trigger Dropdown --}}
    <button type="button" @click="open = !open"
        class="relative w-full px-3 py-2 mt-1 text-left bg-white border rounded-md shadow-sm cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error($name) border-red-500 @else border-gray-300 @enderror">
        <span class="block truncate" x-text="selectedLabel || '{{ $placeholder }}'"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" :class="{ 'transform rotate-180': open }">
                <path fill-rule="evenodd"
                    d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.28a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    {{-- Panel Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg" style="display: none;">
        {{-- Input Pencarian --}}
        <div class="p-2">
            <input type="search" x-model.debounce.300ms="search" placeholder="Cari..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"
                @keydown.escape="open = false">
        </div>

        {{-- Daftar Opsi --}}
        <ul
            class="py-1 overflow-auto text-base max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            <template x-for="([key, option]) in Object.entries(filteredOptions)" :key="key">
                <li @click="selectOption(key, typeof option === 'object' ? ('{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label)) : option)"
                    class="px-3 py-2 text-gray-900 cursor-pointer select-none hover:bg-indigo-600 hover:text-white"
                    :class="{ 'bg-indigo-100': value == key }">
                    <span class="block font-normal truncate"
                        x-text="typeof option === 'object' ? ('{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label)) : option"></span>
                </li>
            </template>

            {{-- Pesan Jika Tidak Ada Hasil --}}
            <template x-if="Object.keys(filteredOptions).length === 0">
                <li class="px-3 py-2 text-gray-500 select-none">Tidak ada hasil ditemukan.</li>
            </template>
        </ul>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
