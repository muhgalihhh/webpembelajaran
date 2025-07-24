<div>
    <x-slot:pageHeader>
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $material ? 'Edit Materi Pembelajaran' : 'Tambah Materi Pembelajaran' }}
        </h2>
    </x-slot:pageHeader>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Kolom Kiri: Input Utama --}}
                <div class="space-y-6 lg:col-span-2">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Materi</label>
                        <input type="text" id="title" wire:model="title" class="w-full p-2 mt-1 border rounded-md">
                        @error('title')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi / Isi
                            Materi</label>
                        <textarea id="description" wire:model="description" class="w-full p-2 mt-1 border rounded-md" rows="10"></textarea>
                        @error('description')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan: Pengaturan & Upload --}}
                <div class="space-y-6 lg:col-span-1">
                    <div class="p-4 border rounded-lg">
                        <h3 class="font-semibold">Pengaturan</h3>
                        <div class="mt-4">
                            <label for="subject_id">Mata Pelajaran</label>
                            <select id="subject_id" wire:model="subject_id" class="w-full p-2 mt-1 border rounded-md">
                                <option value="">Pilih Mapel</option>
                                @foreach ($this->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="is_published">Status Publikasi</label>
                            <select id="is_published" wire:model="is_published"
                                class="w-full p-2 mt-1 border rounded-md">
                                <option value="0">Draft (Disembunyikan)</option>
                                <option value="1">Published (Tampilkan ke siswa)</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-4 border rounded-lg">
                        <h3 class="font-semibold">Lampiran</h3>
                        <div class="mt-4">
                            <label for="url">Link / URL Materi (Opsional)</label>
                            <input type="url" id="url" wire:model="url" placeholder="https://..."
                                class="w-full p-2 mt-1 border rounded-md">
                            @error('url')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="uploadedFile">Upload File (Opsional)</label>
                            <input type="file" id="uploadedFile" wire:model="uploadedFile"
                                class="w-full p-2 mt-1 border rounded-md">
                            <div wire:loading wire:target="uploadedFile" class="mt-1 text-sm text-blue-500">Uploading...
                            </div>
                            @if ($uploadedFile)
                                <p class="mt-1 text-sm text-green-600">File siap diupload:
                                    {{ $uploadedFile->getClientOriginalName() }}</p>
                            @endif
                            @if ($material?->file_path && !$uploadedFile)
                                <p class="mt-1 text-sm text-gray-500">File saat ini: <a
                                        href="{{ Storage::url($material->file_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat File</a></p>
                            @endif
                            @error('uploadedFile')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end pt-6 mt-6 border-t">
                <a href="{{ route('teacher.materials') }}" wire:navigate
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</a>
                <button type="submit"
                    class="px-4 py-2 ml-4 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <span wire:loading.remove wire:target="save">Simpan Materi</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
