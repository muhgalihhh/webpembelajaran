<x-ui.auth-card>
    <x-ui.auth-header>
        MASUK AKUN (Siswa atau Guru)
        <div class="text-sm font-normal mt-1">MEDIA PEMBELAJARAN DIGITAL</div>
    </x-ui.auth-header>

    <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:underline font-bold text-sm"
        wire:navigate>
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Kembali ke Halaman Utama
    </a>

    <form wire:submit="login('siswa')" class="mt-6">
        <x-form.input-group 
            type="text" 
            id="username" 
            placeholder="Masukkan Username" 
            wire:model="username" 
            icon="fa fa-user"
            required 
            autofocus />
        @error('username')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror

        <x-form.input-group 
            type="{{ $showPassword ? 'text' : 'password' }}" 
            id="password" 
            placeholder="Masukkan Kata Sandi" 
            wire:model="password"
            icon="fa fa-lock" 
            required>
            <button 
                type="button" 
                wire:click="togglePassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                <i class="fa {{ $showPassword ? 'fa-eye-slash' : 'fa-eye' }}"></i>
            </button>
        </x-form.input-group>
        @error('password')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror

        <div class="text-right text-sm text-gray-600 mb-6 pr-4">
            Lupa Kata Sandi? <a href="#" class="text-blue-600 hover:underline font-bold">Ubah Kata Sandi</a>
        </div>

        <div class="flex justify-between gap-x-3 mb-6">
            <button 
                type="button"
                wire:click="login('siswa')"
                class="flex-1 bg-[#4A90E2] hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="login('siswa')">Masuk Akun Siswa</span>
                <span wire:loading wire:target="login('siswa')">
                    <i class="fa fa-spinner fa-spin mr-2"></i>Memproses...
                </span>
            </button>
            
            <button 
                type="button"
                wire:click="login('guru')"
                class="flex-1 bg-[#0651a7] hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <span wire:loading.remove wire:target="login('guru')">Masuk Akun Guru</span>
                <span wire:loading wire:target="login('guru')">
                    <i class="fa fa-spinner fa-spin mr-2"></i>Memproses...
                </span>
            </button>
        </div>

        <div class="text-center text-sm text-gray-600">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold"
                wire:navigate>Daftar Sekarang!</a>
        </div>
    </form>
</x-ui.auth-card>
