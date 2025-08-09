<div>
    <x-slot:pageHeader>
        <div class="flex items-center justify-between w-full">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Soal</h2>
                <p class="text-sm text-gray-500">Kuis: {{ $quiz->title }}</p>
            </div>
            <a href="{{ route('teacher.quizzes') }}" wire:navigate
                class="px-3 py-2 text-black transition-colors bg-gray-200 rounded-lg btn btn-sm hover:bg-gray-300">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Kuis
            </a>
        </div>
    </x-slot:pageHeader>

    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-end">
            <x-form.button wireClick="create" icon="fa-solid fa-plus">
                Tambah Soal
            </x-form.button>
        </div>
    </div>

    {{-- Daftar Soal --}}
    <div class="space-y-4">
        @forelse ($questions as $question)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $question->question_number }}.
                            {{ $question->question_text }}</p>

                        @if ($question->image_path)
                            <div class="mt-2">
                                <img src="{{ Storage::url($question->image_path) }}" alt="Gambar Soal"
                                    class="object-cover h-48 rounded-md">
                            </div>
                        @endif

                        <div class="mt-2 space-y-1 text-sm">
                            @foreach (['A', 'B', 'C', 'D', 'E'] as $optionKey)
                                @php $optionValue = 'option_' . strtolower($optionKey); @endphp
                                @if ($question->$optionValue)
                                    <p
                                        class="{{ $question->correct_option == $optionKey ? 'font-bold text-green-600' : 'text-gray-600' }}">
                                        {{ $optionKey }}. {{ $question->$optionValue }}
                                        @if ($question->correct_option == $optionKey)
                                            <i class="ml-2 text-green-500 fa-solid fa-check"></i>
                                        @endif
                                    </p>
                                @endif
                            @endforeach
                        </div>

                        @if ($question->explanation)
                            <div
                                class="p-2 mt-3 text-xs text-gray-700 bg-gray-100 border-l-4 border-gray-300 rounded-r-lg">
                                <strong>Penjelasan:</strong> {{ $question->explanation }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <button wire:click="edit({{ $question->id }})"
                            class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <button wire:click="confirmDelete({{ $question->id }})"
                            class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow-md">
                Belum ada soal untuk kuis ini.
            </div>
        @endforelse
    </div>

    {{-- Modal Form Soal --}}
    <x-ui.modal id="question-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Soal</h2>
        <form wire:submit.prevent="save" class="mt-4">
            {{-- Menggunakan grid untuk layout 2 kolom --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-form.textarea-group label="Teks Pertanyaan" name="question_text" wireModel="question_text"
                        rows="4" required />
                </div>

                <div class="md:col-span-2">
                    <label for="uploadedImage" class="block text-sm font-medium text-gray-700">Gambar (Opsional)</label>
                    <input type="file" id="uploadedImage" wire:model="uploadedImage"
                        class="w-full mt-1 file-input file-input-bordered">
                    <div wire:loading wire:target="uploadedImage" class="mt-1 text-xs text-gray-500">Uploading...</div>
                    @if ($uploadedImage)
                        <img src="{{ $uploadedImage->temporaryUrl() }}" class="h-32 mt-2 rounded-md">
                    @elseif($image_path)
                        <img src="{{ Storage::url($image_path) }}" class="h-32 mt-2 rounded-md">
                    @endif
                    @error('uploadedImage')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Opsi Jawaban --}}
                <x-form.input-group label="Opsi A" type="text" wireModel="option_a" id="option_a" required />
                <x-form.input-group label="Opsi B" type="text" wireModel="option_b" id="option_b" required />
                <x-form.input-group label="Opsi C" type="text" wireModel="option_c" id="option_c" required />
                <x-form.input-group label="Opsi D" type="text" wireModel="option_d" id="option_d" required />
                <div class="md:col-span-2">
                    <x-form.input-group label="Opsi E (Opsional)" type="text" wireModel="option_e" id="option_e" />
                </div>

                {{-- Pengaturan Soal --}}
                <x-form.select-group label="Kunci Jawaban" name="correct_option" wireModel="correct_option"
                    :options="['A' => 'Opsi A', 'B' => 'Opsi B', 'C' => 'Opsi C', 'D' => 'Opsi D', 'E' => 'Opsi E']" required />
                <x-form.input-group label="Bobot Nilai" type="number" wireModel="weight" id="weight" required />

                <div class="md:col-span-2">
                    <x-form.textarea-group label="Penjelasan (Opsional)" name="explanation" wireModel="explanation"
                        rows="2" />
                </div>
            </div>

            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <x-form.button type="button" wireClick="closeModal" icon="fa-solid fa-times" variant="secondary"
                    class="btn btn-secondary">
                    Batal
                </x-form.button>
                <x-form.button type="submit" icon="fa-solid fa-save" variant="primary" class="btn btn-primary">
                    Simpan
                </x-form.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Soal" message="Anda yakin ingin menghapus soal ini?" wireConfirmAction="delete" />
</div>
