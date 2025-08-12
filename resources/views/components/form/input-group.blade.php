@props([
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'wireModel' => '',
    'label' => '',
    'icon' => '',
    'passwordToggle' => false,
    'accept' => '',
    'multiple' => false,
    'customClass' => '',
])

{{-- Wrapper utama tanpa margin atau lebar tetap --}}
<div {{ $attributes->except('class') }} class="mb-2">
    @if ($label)
        <label for="{{ $id }}" class="block mb-1 text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    @if ($type === 'file')
        {{-- File Upload Styling --}}
        <div x-data="{
            dragOver: false,
            fileName: '',
            fileSize: '',
            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        }" class="relative">

            <div class="relative p-6 text-center transition-colors duration-200 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 {{ $customClass }}"
                :class="{ 'border-indigo-400 bg-indigo-50': dragOver }" @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false" @drop.prevent="dragOver = false">

                <input type="file" id="{{ $id }}" wire:model="{{ $wireModel }}"
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer"
                    @if ($accept) accept="{{ $accept }}" @endif
                    @if ($multiple) multiple @endif
                    @change="
                        const files = $event.target.files;
                        if (files.length > 0) {
                            if (files.length === 1) {
                                fileName = files[0].name;
                                fileSize = formatFileSize(files[0].size);
                            } else {
                                fileName = files.length + ' files selected';
                                fileSize = '';
                            }
                        } else {
                            fileName = '';
                            fileSize = '';
                        }
                    ">

                <div class="space-y-2" :class="{ 'text-indigo-600': dragOver }">
                    @if ($icon)
                        <i class="{{ $icon }} text-2xl text-gray-400"
                            :class="{ 'text-indigo-500': dragOver }"></i>
                    @else
                        <svg class="w-12 h-12 mx-auto text-gray-400" :class="{ 'text-indigo-500': dragOver }"
                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @endif

                    <div class="text-sm">
                        <span x-show="!fileName" class="text-gray-600">
                            <span class="font-medium text-indigo-600 cursor-pointer hover:text-indigo-500">
                                {{ $placeholder ?: 'Click to upload' }}
                            </span>
                            <span class="text-gray-500"> or drag and drop</span>
                        </span>

                        <div x-show="fileName" class="text-gray-800">
                            <div class="font-medium" x-text="fileName"></div>
                            <div x-show="fileSize" class="mt-1 text-xs text-gray-500" x-text="fileSize"></div>
                        </div>
                    </div>
                    @if ($accept)
                        <p class="text-xs text-gray-500">{{ $accept }}</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div x-data="{ show: false }"
            class="flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">

            @if ($icon)
                <span class="mr-2 text-gray-500">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif

            <input :type="show ? 'text' : '{{ $type }}'" id="{{ $id }}"
                placeholder="{{ $placeholder }}" wire:model.live.debounce.300ms="{{ $wireModel }}"
                class="flex-grow w-full text-base text-gray-800 placeholder-gray-400 bg-transparent outline-none">

            @if ($passwordToggle)
                <span class="ml-3 text-gray-500 cursor-pointer" @click="show = !show">
                    <i class="fa" :class="{ 'fa-eye-slash': !show, 'fa-eye': show }"></i>
                </span>
            @endif
        </div>
    @endif

    @error($wireModel)
        <span class="block mt-1 text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
