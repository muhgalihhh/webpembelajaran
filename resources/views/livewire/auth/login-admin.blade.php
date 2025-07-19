<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Admin)
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:underline font-bold text-sm"
        wire:navigate>
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Kembali ke Halaman Utama
    </a>

    {{-- Form ini akan mengirimkan aksi melalui tombol wireClick, jadi tidak perlu wire:submit.prevent pada form --}}
    <form class="mt-6" wire:submit.prevent="login">

        @csrf

        <x-form.input-group type="text" id="username" placeholder="Masukkan Username" wire:model.live="username"
            {{-- Diubah dari model="username" --}} icon="fa fa-user" required autofocus />
        @error('username')
            <small class="text-red-500 text-sm">{{ $message }}</small>
        @enderror {{-- Tambahkan ini jika input-group Anda tidak otomatis menampilkan error --}}

        <x-form.input-group type="password" id="password" placeholder="Masukkan Kata Sandi" wire:model.live="password"
            {{-- Diubah dari model="password" --}} icon="fa fa-lock" passwordToggle required />
        @error('password')
            <small class="text-red-500 text-sm">{{ $message }}</small>
        @enderror {{-- Tambahkan ini jika input-group Anda tidak otomatis menampilkan error --}}



        <div class="flex justify-between gap-x-3 mb-6">

            <x-form.button type="button" wire:click="authenticate" label="Masuk Akun Admin" {{-- Perbaikan wireClick menjadi wire:click --}}
                class="flex-1 bg-[#0651a7]" />
        </div>

    </form>

</x-ui.auth-card>
