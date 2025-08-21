<div>
    {{-- Header --}}
    <div class="p-4 bg-white border-b border-gray-200 shadow-sm sm:p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $material->exists ? 'Edit Materi' : 'Tambah Materi Baru' }}
            </h1>
            <a href="{{ route('teacher.materials') }}" wire:navigate class="btn btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="p-4 sm:p-6">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <form wire:submit.prevent="save">
                <div class="space-y-6">


                    <div>
                        <label for="chapter" class="block text-sm font-medium text-gray-700">Bab / Topik</label>
                        <input type="text" id="chapter" wire:model="chapter"
                            placeholder="Contoh: Bab 1 - Sejarah Kemerdekaan"
                            class="block w-full px-4 py-3 mt-1 text-2xl font-bold border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('chapter')
                            <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Judul Materi --}}
                    <x-form.input-group label="Judul Materi" name="title" wireModel="title"
                        error="{{ $errors->first('title') }}" />

                    {{-- Deskripsi Singkat --}}
                    <x-form.textarea-group label="Deskripsi Singkat" name="description" wireModel="description"
                        placeholder="Tulis ringkasan singkat tentang materi ini..."
                        error="{{ $errors->first('description') }}" />

                    {{-- Trix Editor untuk Konten Lengkap --}}
                    <div wire:ignore x-data="{
                        value: @entangle('content'),
                        isFocused: false,
                        init() {
                            let trixEditor = this.$refs.trix;
                            trixEditor.editor.loadHTML(this.value);
                            trixEditor.addEventListener('trix-focus', () => this.isFocused = true);
                            trixEditor.addEventListener('trix-blur', () => this.isFocused = false);
                            trixEditor.addEventListener('trix-change', (e) => {
                                this.value = e.target.value;
                            });
                        }
                    }" x-init="init()" class="mt-1">
                        <label for="content" class="block text-sm font-medium text-gray-700">Konten Lengkap
                            Materi</label>
                        <input id="content" type="hidden" :value="value">
                        <trix-editor x-ref="trix" input="content" :class="{ 'trix-content-focused': isFocused }"
                            class="trix-content"></trix-editor>
                        @error('content')
                            <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Mata Pelajaran & Kelas --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id"
                            :options="$this->subjects" error="{{ $errors->first('subject_id') }}" />
                        <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                            optionLabel="class" error="{{ $errors->first('class_id') }}" />
                    </div>

                    <x-form.input-group label="URL Video Youtube (Opsional)" name="url" wireModel="url"
                        placeholder="https://www.youtube.com/watch?v=..." error="{{ $errors->first('url') }}" />

                    {{-- Input File atau Link --}}
                    <div x-data="{ inputType: @entangle('inputType').live }">
                        <label class="block text-sm font-medium text-gray-700">File Materi (Opsional)</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" x-model="inputType" value="file" class="form-radio">
                                <span class="ml-2">Unggah File</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" x-model="inputType" value="link" class="form-radio">
                                <span class="ml-2">Link Eksternal</span>
                            </label>
                        </div>

                        <div x-show="inputType === 'file'" class="mt-4">
                            <div class="flex items-center justify-between">
                                <label for="uploadedFile" class="block text-sm font-medium text-gray-700">File Materi
                                    (Opsional)</label>
                                <span class="text-xs text-gray-500">Maks: 10MB</span>
                            </div>
                            <x-form.input-group type="file" id="uploadedFile" wireModel="uploadedFile"
                                class="w-full mt-1 file-input file-input-bordered" />
                            <div wire:loading wire:target="uploadedFile" class="mt-2 text-sm text-gray-500">Uploading...
                            </div>
                            @if ($page_count)
                                <div class="mt-2 text-sm">
                                    <span class="font-medium text-gray-700">Hasil Deteksi PDF:</span>
                                    <span
                                        class="px-2 py-1 ml-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">{{ $page_count }}
                                        Halaman</span>
                                </div>
                            @endif
                            @if ($material->exists && $currentFileUrl && !$uploadedFile)
                                <div class="mt-2 text-sm">
                                    File saat ini:
                                    <a href="{{ route('materials.view', $material) }}" target="_blank"
                                        class="text-blue-600 hover:underline"><i class="mr-1 fas fa-eye"></i> Lihat
                                        File</a>
                                    <span class="mx-1 text-gray-300">|</span>
                                    <a href="{{ route('materials.download', $material) }}"
                                        class="text-blue-600 hover:underline"><i class="mr-1 fas fa-download"></i>
                                        Unduh</a>
                                    @if ($material->page_count)
                                        <span class="ml-2 text-gray-500">({{ $material->page_count }} Halaman)</span>
                                    @endif
                                </div>
                            @endif
                            @error('uploadedFile')
                                <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div x-show="inputType === 'link'" class="mt-4">
                            <x-form.input-group label="URL File Materi" name="file_path" wireModel="file_path"
                                placeholder="https://docs.google.com/..." error="{{ $errors->first('file_path') }}" />
                        </div>
                    </div>

                    <div x-data="{ isPublished: @entangle('is_published').live }">
                        <div class="flex items-center">
                            <input id="is_published" type="checkbox" wire:model.live="is_published"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="is_published" class="block ml-2 text-sm text-gray-900">Publikasikan
                                Materi</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-8 mt-8 text-white border-t">
                    <a href="{{ route('teacher.materials') }}" wire:navigate
                        class="px-3 py-2 mr-3 text-gray-800 bg-gray-200 rounded-lg btn hover:bg-gray-300">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="px-3 py-2 bg-blue-600 rounded-lg btn btn-primary hover:bg-blue-700"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="mr-2 fas fa-save"></i>
                            Simpan Materi</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .trix-button-group--file-tools {
            display: none !important;
        }

        .trix-content {
            min-height: 250px;
            background-color: #fff;
            border-color: #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            padding: 0.75rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .trix-content.trix-content-focused {
            outline: 2px solid transparent;
            outline-offset: 2px;
            --tw-ring-color: #4338ca;
            box-shadow: 0 0 0 2px var(--tw-ring-color);
            border-color: #6366f1;
        }
    </style>
</div>
