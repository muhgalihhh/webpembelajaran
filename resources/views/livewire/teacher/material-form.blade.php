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

                    {{-- Mata Pelajaran, Kelas & Bab --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <x-form.select-group label="Mata Pelajaran" name="subject_id" wireModel="subject_id"
                            :options="$this->subjects" error="{{ $errors->first('subject_id') }}" />

                        <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->classes"
                            optionLabel="class" error="{{ $errors->first('class_id') }}" />

                        <x-form.input-group label="Bab / Topik" name="chapter" wireModel="chapter"
                            placeholder="Contoh: Bab 1" error="{{ $errors->first('chapter') }}" />
                    </div>

                    <x-form.input-group label="URL Video Youtube (Opsional)" name="url" wireModel="url"
                        placeholder="https://youtube.com/watch?v=xxxx" error="{{ $errors->first('url') }}" />
                    <div>
                        <label for="uploadedFile" class="block text-sm font-medium text-gray-700">File Materi
                            (Opsional)</label>
                        <input type="file" id="uploadedFile" wire:model="uploadedFile"
                            class="w-full mt-1 file-input file-input-bordered">
                        <div wire:loading wire:target="uploadedFile" class="mt-2 text-sm text-gray-500">Uploading...
                        </div>
                        @if ($currentFileUrl)
                            <div class="mt-2 text-sm">
                                File saat ini: <a href="{{ $currentFileUrl }}" target="_blank"
                                    class="text-blue-600 hover:underline">Lihat File</a>
                            </div>
                        @endif
                        @error('uploadedFile')
                            <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ isPublished: @entangle('is_published').live }">
                        <div class="flex items-center">
                            <input id="is_published" type="checkbox" wire:model.live="is_published"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="is_published" class="block ml-2 text-sm text-gray-900">Publikasikan
                                Materi</label>
                        </div>

                        <div x-show="isPublished" x-transition.opacity.duration.500ms class="mt-4">
                            <x-form.input-group type="datetime-local" label="Jadwalkan Tanggal Publikasi (Opsional)"
                                name="published_at" wireModel="published_at"
                                error="{{ $errors->first('published_at') }}">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk publikasi segera.</p>
                            </x-form.input-group>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end pt-8 mt-8 border-t">
                    <a href="{{ route('teacher.materials') }}" wire:navigate class="mr-3 btn">Batal</a>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Simpan Materi</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kustomisasi Styling untuk Trix Editor --}}
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
