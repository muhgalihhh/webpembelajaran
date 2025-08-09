<div>
    {{-- Header Halaman --}}
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Siswa</h2>
    </x-slot:pageHeader>

    {{-- Bagian Filter dan Pencarian --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Siswa" type="search" wireModel="search" id="search"
                    placeholder="Cari nama, email, atau no. telp..." />
            </div>
            <div class="flex-1">
                <x-form.select-group label="Filter Kelas" name="class_filter" wireModel="class_filter" :options="$this->availableClasses"
                    wire:model.live='class_filter' optionLabel="class" />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Siswa
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tampilan Tabel untuk Desktop (Terlihat di layar lg ke atas) --}}
    <div class="hidden overflow-x-auto bg-white rounded-lg shadow-md lg:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama</th>
                    <th wire:click="sortBy('email')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Email / No. Telp</th>
                    <th wire:click="sortBy('class_id')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Kelas</th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->students as $student)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->email }}</div>
                            <div class="text-sm text-gray-500">{{ $student->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->class->class ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="view({{ $student->id }})"
                                class="text-blue-600 hover:text-blue-900 btn">
                                <i class="fa-solid fa-eye"></i> Detail
                            </button>
                            <button wire:click="edit({{ $student->id }})"
                                class="ml-4 text-indigo-600 hover:text-indigo-900">
                                <i class="fa-solid fa-pencil-alt"></i> Edit
                            </button>
                            <button wire:click="confirmDelete({{ $student->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada data siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilan Kartu untuk Mobile (Tersembunyi di layar lg ke atas) --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($this->students as $student)
            <div class="p-4 bg-white rounded-lg shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-lg font-bold text-gray-800">{{ $student->name }}</div>
                        <div class="text-sm text-gray-600">{{ $student->class->class ?? 'N/A' }}</div>
                    </div>
                    <span
                        class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <p>{{ $student->email }}</p>
                    <p>{{ $student->phone_number }}</p>
                </div>
                <div class="flex justify-end mt-4 space-x-4">
                    <button wire:click="view({{ $student->id }})"
                        class="text-blue-600 hover:text-blue-900">Detail</button>
                    <button wire:click="edit({{ $student->id }})"
                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button wire:click="confirmDelete({{ $student->id }})"
                        class="text-red-600 hover:text-red-900">Hapus</button>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">Tidak ada data siswa ditemukan.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $this->students->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="student-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Siswa</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-form.input-group label="Nama" type="text" wireModel="name" id="name" />
                <x-form.input-group label="Username" type="text" wireModel="username" id="username" />
                <x-form.input-group label="Email" type="email" wireModel="email" id="email" />
                <x-form.input-group label="No. Telepon (Opsional)" type="tel" wireModel="phone_number"
                    id="phone_number" />
                <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->availableClasses"
                    optionLabel="class" />
                <x-form.select-group label="Status" name="status" wireModel="status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />
                <x-form.input-group label="Password" type="password" wireModel="password" id="password"
                    :required="!$isEditing" passwordToggle />
                <x-form.input-group label="Konfirmasi Password" type="password" wireModel="password_confirmation"
                    id="password_confirmation" passwordToggle />
            </div>

            <div class="flex justify-end pt-4 mt-4 space-x-4 border-t">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Konfirmasi Hapus --}}
    <x-ui.confirm-modal title="Hapus Siswa" message="Anda yakin ingin menghapus data siswa ini?"
        wireConfirmAction="delete" />

    {{-- Modal Detail Student --}}
    <x-ui.user-detail-modals :user="$viewingUser" />
</div>
