{{-- resources/views/livewire/admin/crud-table.blade.php --}}

<div class="py-6">
    {{-- Dynamic Page Title --}}
    @php
        $pageTitle = 'Manajemen Data';
        if ($modelName === 'User') {
            if ($roleFilter === 'guru') {
                $pageTitle = 'Manajemen Guru';
            } elseif ($roleFilter === 'siswa') {
                $pageTitle = 'Manajemen Siswa';
            }
        } elseif ($modelName === 'Classes') {
            $pageTitle = 'Manajemen Kelas';
        } elseif ($modelName === 'Subject') {
            $pageTitle = 'Manajemen Mata Pelajaran';
        }
    @endphp
    <x-slot:pageHeader>
        <div class="flex items-center">
            {{-- Tombol Hamburger untuk Mobile --}}
            <button @click.stop="mobileSidebarOpen = !mobileSidebarOpen" class="mr-4 text-gray-600 lg:hidden">
                <i class="text-xl fa-solid fa-bars"></i>
            </button>
            {{-- Judul Halaman --}}
            <h2 class="text-2xl font-bold text-gray-800">
                {{ $pageTitle }}
            </h2>
        </div>
    </x-slot:pageHeader>

    {{-- Filter and Search Section --}}
    <div class="p-6 mb-6 bg-white border shadow-md">
        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">
            {{-- Search Input --}}
            <div class="md:col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Cari:</label>
                <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Cari..."
                    class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            {{-- Dynamic Filters based on model --}}
            @if ($modelName === 'User')
                <div>

                    <label for="status_filter" class="block text-sm font-medium text-gray-700">
                        Filter Status
                    </label>


                    <div class="relative mt-1">
                        <select wire:model.live="status_filter" id="status_filter"
                            class="block w-full px-3 py-2 pr-10 text-base bg-white border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>


                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                @if ($roleFilter === 'siswa')
                    <div class="md:col-span-1">
                        {{-- Label untuk filter kelas --}}
                        <label for="class_id_filter" class="block text-sm font-medium text-gray-700">
                            Filter Kelas
                        </label>

                        {{-- Wrapper untuk posisi ikon --}}
                        <div class="relative mt-1">
                            <select wire:model.live="class_id_filter" id="class_id_filter"
                                class="block w-full px-3 py-2 pr-10 text-base bg-white border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Kelas</option>
                                @foreach ($availableClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>

                            {{-- Ikon panah dropdown --}}
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($modelName === 'Subject')
                <div class="md:col-span-1">
                    {{-- Label untuk filter --}}
                    <label for="is_active_filter" class="block text-sm font-medium text-gray-700">
                        Filter Status
                    </label>

                    {{-- Wrapper untuk posisi ikon --}}
                    <div class="relative mt-1">
                        <select wire:model.live="is_active_filter" id="is_active_filter"
                            class="block w-full px-3 py-2 pr-10 text-base bg-white border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        {{-- Ikon panah dropdown kustom --}}
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 pointer-events-none">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.28a.75.75 0 011.06 0L10 15.19l2.67-2.91a.75.75 0 111.06 1.06l-3.5 3.5a.75.75 0 01-1.06 0l-3.5-3.5a.75.75 0 010-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-3">
            <button wire:click="create" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                <i class="mr-2 fas fa-plus-circle"></i>Tambah Baru
            </button>
            {{-- Export Excel Button --}}
            <button wire:click="exportRecords" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                <i class="mr-2 fas fa-file-excel"></i>Export ke Excel
            </button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="p-6 bg-white border shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="border ">
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID
                        </th>
                        @if ($modelName === 'User')
                            <th wire:click="sortBy('name')"
                                class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Nama Lengkap
                                @if ($sortBy === 'name')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('username')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Username
                                @if ($sortBy === 'username')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Email</th>
                            @if ($roleFilter === 'siswa')
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Kelas</th>
                            @endif
                            <th wire:click="sortBy('status')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Status
                                @if ($sortBy === 'status')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                        @elseif ($modelName === 'Classes')
                            <th wire:click="sortBy('name')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Nama Kelas
                                @if ($sortBy === 'name')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Deskripsi</th>
                        @elseif ($modelName === 'Subject')
                            <th wire:click="sortBy('name')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Nama Mapel
                                @if ($sortBy === 'name')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('code')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Kode
                                @if ($sortBy === 'code')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('is_active')"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer">
                                Aktif
                                @if ($sortBy === 'is_active')
                                    <i class="fas {{ $sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>
                                @else
                                    <i class="text-gray-300 fas fa-sort"></i>
                                @endif
                            </th>
                        @endif
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white border divide-y divide-gray-200">
                    @forelse ($records as $index => $record)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                {{ ($records->currentPage() - 1) * $records->perPage() + $index + 1 }}
                            </td>
                            @if ($modelName === 'User')
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $record->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $record->username }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $record->email }}
                                </td>
                                @if ($roleFilter === 'siswa')
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $record->class->name ?? 'N/A' }}</td>
                                @endif
                                <td class="px-6 py-4 text-sm text-gray-900 capitalize whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $record->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $record->status }}
                                    </span>
                                </td>
                            @elseif ($modelName === 'Classes')
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $record->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    {{ Str::limit($record->description, 50) }}</td>
                            @elseif ($modelName === 'Subject')
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $record->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $record->code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $record->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $record->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            @endif
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <x-form.button wire:click="edit({{ $record->id }})"
                                    class="px-3 py-1 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50">
                                    <i class="mr-1 fas fa-edit"></i>
                                    Edit
                                </x-form.button>
                                @if ($modelName === 'User')
                                    <button wire:click="confirmResetPassword({{ $record->id }})"
                                        {{-- Memanggil konfirmasi --}}
                                        class="px-3 py-1 ml-2 text-white bg-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                                        <i class="mr-1 fas fa-key"></i> Reset Pass
                                    </button>
                                @endif
                                <x-form.button wire:click="confirmDelete({{ $record->id }})" {{-- Memanggil konfirmasi --}}
                                    class="px-3 py-1 ml-2 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    <i class="mr-1 fas fa-trash"></i>
                                    Hapus
                                </x-form.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $records->links() }}
        </div>
    </div>

    <div x-data="{ show: @entangle('showFormModal').live }" x-show="show" x-cloak x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.5);" @click.away="$wire.showFormModal = false">

        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="w-full max-w-md p-6 mx-4 bg-white rounded-lg shadow-xl" @click.stop>

            <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">
                {{ $isEditing ? 'Edit' : 'Create New' }}
                {{ $modelName === 'User' ? ($roleFilter ? ucfirst($roleFilter) : 'User') : Str::singular($modelName) }}
            </h3>

            <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
                @csrf

                @if ($modelName === 'User')
                    <x-form.input-group type="text" id="name" placeholder="Full Name" model="name"
                        icon="fa-solid fa-user" required />
                    <x-form.input-group type="text" id="username" placeholder="Username" model="username"
                        icon="fa-solid fa-id-badge" required />
                    <x-form.input-group type="email" id="email" placeholder="Email" model="email"
                        icon="fa-solid fa-envelope" required />

                    <x-form.input-group type="password" id="password"
                        placeholder="Password (leave blank to keep current)" model="password" icon="fa-solid fa-lock"
                        passwordToggle />
                    <x-form.input-group type="password" id="password_confirmation" placeholder="Confirm Password"
                        model="password_confirmation" icon="fa-solid fa-key" passwordToggle />

                    @if ($roleFilter === 'siswa')
                        <div class="mb-5">
                            <label for="class_id" class="block mb-2 text-sm font-medium text-gray-700">Class</label>
                            <div class="relative">
                                <select id="class_id" wire:model="class_id"
                                    class="block w-full px-4 py-2 pr-8 leading-tight bg-white border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Class</option>
                                    @foreach ($availableClasses as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                                    {{-- Font Awesome icon for dropdown arrow --}}
                                    <i class="text-gray-500 fas fa-caret-down"></i>
                                </div>
                            </div>
                            @error('class_id')
                                <span class="block mt-2 text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-5">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                        <div class="relative">
                            <select id="status" wire:model="status"
                                class="block w-full px-4 py-2 pr-8 leading-tight bg-white border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                                {{-- Font Awesome icon for dropdown arrow --}}
                                <i class="text-gray-500 fas fa-caret-down"></i>
                            </div>
                        </div>
                        @error('status')
                            <span class="block mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                @elseif ($modelName === 'Classes')
                    <x-form.input-group type="text" id="item_name" placeholder="Class Name" model="item_name"
                        icon="fa-solid fa-school" required />
                    <div class="mb-5">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" wire:model="description" rows="3"
                            class="w-full p-3 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('description')
                            <span class="block mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                @elseif ($modelName === 'Subject')
                    <x-form.input-group type="text" id="item_name" placeholder="Subject Name" model="item_name"
                        icon="fa-solid fa-book-open" required />
                    <x-form.input-group type="text" id="code" placeholder="Subject Code" model="code"
                        icon="fa-solid fa-barcode" required />
                    <div class="flex items-center mb-5">
                        <input type="checkbox" id="is_active" wire:model="is_active"
                            class="mr-2 text-blue-600 border-gray-300 rounded shadow-sm focus:ring-blue-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Is Active?</label>
                        @error('is_active')
                            <span class="block ml-3 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="flex justify-end mt-6 space-x-3">
                    <x-form.button type="button" wire:click="resetForm(); $wire.showFormModal = false;"
                        class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                        Cancel
                    </x-form.button>
                    <x-form.button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <span wire:loading.remove wire:target="{{ $isEditing ? 'update' : 'store' }}">
                            {{ $isEditing ? 'Save Changes' : 'Create' }}
                        </span>
                        <span wire:loading wire:target="{{ $isEditing ? 'update' : 'store' }}">
                            {{ $isEditing ? 'Saving...' : 'Creating...' }}
                        </span>
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>
