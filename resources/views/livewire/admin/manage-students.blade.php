<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Siswa</h2>
    </x-slot:pageHeader>

    {{-- Filter Section --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <x-form.input-group type="search" id="search" placeholder="Cari nama atau email..." wireModel="search"
                    icon="fa-solid fa-search" label="Pencarian Siswa" />
            </div>
            <div>
                <x-form.select-group label="Filter Kelas" name="class_id" wireModel="class_id" :options="$this->availableClasses"
                    placeholder="Semua Kelas" />
            </div>
            <div class="flex items-end justify-end ">
                <x-form.button type="button" variant="primary" icon="fa-solid fa-plus" wireClick="create">
                    Tambah Siswa
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Siswa --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama</th>
                    <th wire:click="sortBy('email')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Email</th>
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
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->class->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="edit({{ $student->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $student->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
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

    <div class="mt-4">{{ $this->students->links() }}</div>

    <x-ui.confirm-modal title="Hapus Siswa" message="Anda yakin ingin menghapus data siswa ini?"
        wireConfirmAction="delete" />

    <x-ui.modal id="student-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Siswa</h2>
        <form wire:submit.prevent="save" class="mt-4 space-y-4">
            <x-form.input-group label="Nama" type="text" wireModel="name" id="name" />
            <x-form.input-group label="Username" type="text" wireModel="username" id="username" />
            <x-form.input-group label="Email" type="email" wireModel="email" id="email" />
            <x-form.select-group label="Kelas" name="class_id" wireModel="class_id" :options="$this->availableClasses" />
            <x-form.input-group label="Password" type="password" wireModel="password" id="password" :required="!$isEditing"
                passwordToggle />
            <x-form.input-group label="Konfirmasi Password" type="password" wireModel="password_confirmation"
                id="password_confirmation" passwordToggle :required="!$isEditing" />
            <x-form.select-group label="Status" name="status" wireModel="status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />

            <div class="flex justify-end pt-4 space-x-4">
                <button type="button" @click="$dispatch('close-modal')"
                    class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </x-ui.modal>

</div>
