<div>
    <x-slot:pageHeader>
        <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Guru</h2>
    </x-slot:pageHeader>

    {{-- Filter Section --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-form.input-group label="Pencarian Guru" type="search" wireModel="search" id="search"
                    placeholder="Cari nama, email, atau no. telp..." />
            </div>
            <div class="flex-1">
                <x-form.select-group label="Filter Status" name="status_filter" wireModel="status_filter"
                    :options="['all' => 'Semua Status', 'active' => 'Aktif', 'inactive' => 'Nonaktif']" />
            </div>
            <div>
                <x-form.button wireClick="create" icon="fa-solid fa-plus" class="w-full md:w-auto">
                    Tambah Guru
                </x-form.button>
            </div>
        </div>
    </div>

    {{-- Tabel Data Guru --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Nama</th>
                    <th wire:click="sortBy('email')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Email / No. Telp</th>
                    <th wire:click="sortBy('status')"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                        Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->teachers as $teacher)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $teacher->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $teacher->email }}</div>
                            <div class="text-sm text-gray-500">{{ $teacher->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <button wire:click="view({{ $teacher->id }})"
                                class="text-blue-600 hover:text-blue-900 btn">
                                <i class="fa-solid fa-eye"></i>
                                Detail</button>
                            <button wire:click="edit({{ $teacher->id }})"
                                class="ml-4 text-indigo-600 hover:text-indigo-900">
                                <i class="fa-solid fa-pencil-alt"></i>
                                Edit</button>
                            <button wire:click="confirmDelete({{ $teacher->id }})"
                                class="ml-4 text-red-600 hover:text-red-900">
                                <i class="fa-solid fa-trash"></i>
                                Hapus</button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data guru ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->teachers->links() }}</div>

    {{-- Modal Form --}}
    <x-ui.modal id="teacher-form-modal">
        <h2 class="text-2xl font-bold">{{ $isEditing ? 'Edit' : 'Tambah' }} Guru</h2>
        <form wire:submit.prevent="save" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-form.input-group label="Nama" type="text" wireModel="name" id="name" />
                <x-form.input-group label="Username" type="text" wireModel="username" id="username" />
                <x-form.input-group label="Email" type="email" wireModel="email" id="email" />
                <x-form.input-group label="No. Telepon (Opsional)" type="tel" wireModel="phone_number"
                    id="phone_number" />
                <x-form.select-group label="Status" name="status" wireModel="status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />
                {{-- Spacer div to align password fields correctly --}}
                <div></div>
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

    {{-- Modal Konfirmasi Delete --}}
    <x-ui.confirm-modal title="Hapus Guru" message="Anda yakin ingin menghapus data guru ini?"
        wireConfirmAction="delete" />

    {{-- Modal Detail Pengguna --}}
    <x-ui.user-detail-modals :user="$viewingUser" />


</div>
