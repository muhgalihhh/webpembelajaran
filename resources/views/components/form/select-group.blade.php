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
    value: @entangle($wireModel).live,
    selectedLabel: '',

    // Gabungkan placeholder sebagai opsi pertama
    get optionsWithPlaceholder() {
        const placeholderOption = { '': '{{ $placeholder }}' };
        // Cek apakah options adalah array of objects atau key-value
        if (Array.isArray({{ json_encode($options) }})) {
            // Konversi array of objects ke format yang benar
            const formattedOptions = {{ json_encode($options) }}.reduce((acc, item) => {
                acc[item.id] = item;
                return acc;
            }, {});
            return { ...placeholderOption, ...formattedOptions };
        }
        return { ...placeholderOption, ...{{ json_encode($options) }} };
    },

    get filteredOptions() {
        if (this.search === '') {
            return this.optionsWithPlaceholder;
        }
        const searchLower = this.search.toLowerCase();

        if (typeof Object.values(this.optionsWithPlaceholder)[1] === 'object') { // Cek dari indeks ke-1
            return Object.fromEntries(
                Object.entries(this.optionsWithPlaceholder).filter(([key, item]) => {
                    if (key === '') return true; // Selalu tampilkan placeholder
                    const label = '{{ $optionLabel }}' ? item['{{ $optionLabel }}'] : item.name || item.label;
                    return label.toLowerCase().includes(searchLower);
                })
            );
        } else {
            return Object.fromEntries(
                Object.entries(this.optionsWithPlaceholder).filter(([key, label]) =>
                    label.toLowerCase().includes(searchLower)
                )
            );
        }
    },

    selectOption(key, label) {
        this.value = key;
        // Jika placeholder dipilih, set label sesuai placeholder
        this.selectedLabel = (key === '') ? '{{ $placeholder }}' : label;
        this.open = false;
        this.search = '';
    },

    getLabelFromValue(val) {
        // Handle jika value kosong atau tidak ada di options
        if (!val || !this.optionsWithPlaceholder[val]) {
            return '{{ $placeholder }}';
        }
        const option = this.optionsWithPlaceholder[val];

        if (typeof option === 'object') {
            return '{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label || val);
        }
        return option;
    },

    init() {
        this.selectedLabel = this.getLabelFromValue(this.value);

        this.$watch('value', (newValue) => {
            this.selectedLabel = this.getLabelFromValue(newValue);
        });

        // Tidak perlu watch 'options', karena sudah digabung di `optionsWithPlaceholder`
    }
}" x-init="init()" class="relative" @click.outside="open = false">
    {{-- Label --}}
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
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

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-[1000] mt-1 bg-white border border-gray-300 rounded-md shadow-lg min-w-max max-w-xs"
        style="display: none;">
        {{-- Search Input --}}
        <div class="sticky top-0 z-10 p-3 bg-white border-b border-gray-200 rounded-t-md">
            <input type="search" x-model.debounce.300ms="search" placeholder="Cari..."
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                @keydown.escape="open = false" @keydown.enter.prevent="">
        </div>

        {{-- Dropdown List dengan tinggi tetap --}}
        <div class="relative">
            <ul
                class="py-1 overflow-y-auto text-sm bg-white rounded-b-md max-h-48 ring-1 ring-black ring-opacity-5 focus:outline-none">
                <template x-for="([key, option]) in Object.entries(filteredOptions)" :key="key">
                    <li @click="selectOption(key, typeof option === 'object' ? ('{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label)) : option)"
                        class="relative px-4 py-2 text-gray-900 cursor-pointer select-none hover:bg-indigo-100 focus:bg-indigo-100 whitespace-nowrap"
                        :class="{
                            'bg-indigo-600 text-white hover:bg-indigo-700': value == key && key !== '',
                            'bg-gray-50': value == key && key === '',
                            'font-medium': key === ''
                        }">
                        <span class="block truncate"
                            x-text="typeof option === 'object' ? ('{{ $optionLabel }}' ? option['{{ $optionLabel }}'] : (option.name || option.label)) : option"></span>

                        {{-- Checkmark untuk item yang dipilih --}}
                        <span x-show="value == key && key !== ''"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </li>
                </template>

                {{-- Pesan Jika Tidak Ada Hasil --}}
                <template x-if="Object.keys(filteredOptions).length === 1 && Object.keys(filteredOptions)[0] === ''">
                    <li class="px-4 py-3 text-center text-gray-500 select-none">
                        <div class="flex flex-col items-center space-y-1">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.462-.881-6.065-2.33C5.93 12.658 6 12.34 6 12c0-.34-.07-.658-.065-.67A7.962 7.962 0 0112 9c2.34 0 4.462.881 6.065 2.33.005.012-.065.33-.065.67 0 .34.07.658.065.67z">
                                </path>
                            </svg>
                            <span class="text-sm">Tidak ada hasil ditemukan</span>
                        </div>
                    </li>
                </template>

                {{-- Loading state (opsional) --}}
                <template x-if="false">
                    <li class="px-4 py-3 text-center text-gray-500 select-none">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full animate-spin border-t-indigo-600">
                            </div>
                            <span class="text-sm">Memuat...</span>
                        </div>
                    </li>
                </template>
            </ul>

            {{-- Scrollbar custom styling --}}
            <style>
                /* Custom scrollbar untuk dropdown */
                .max-h-48::-webkit-scrollbar {
                    width: 6px;
                }

                .max-h-48::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 3px;
                }

                .max-h-48::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 3px;
                }

                .max-h-48::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            </style>
        </div>

        <div x-show="Object.keys(filteredOptions).length > 1"
            class="px-3 py-2 text-xs text-gray-500 border-t border-gray-200 bg-gray-50 rounded-b-md">
            <span x-text="`${Object.keys(filteredOptions).length - 1} item tersedia`"></span>
            <template x-if="search !== ''">
                <span x-text="`dari pencarian '${search}'`"></span>
            </template>
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
