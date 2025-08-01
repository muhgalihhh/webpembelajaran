<?php

namespace App\Livewire\Auth;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Daftar Akun')]
class Register extends Component
{
    // Properti Form
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $phone_number = ''; // Kolom baru
    public string $password = '';
    public string $password_confirmation = '';
    public $class_id = ''; // Kolom baru untuk siswa

    // Properti untuk mengontrol tab
    public string $activeTab = 'siswa';

    /**
     * Mengambil daftar kelas untuk dropdown.
     */
    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
    }

    /**
     * Mengganti tab aktif.
     */
    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation(); // Hapus error validasi saat ganti tab
    }

    /**
     * Metode utama yang dipanggil saat form disubmit.
     */
    public function register()
    {
        // Memanggil metode registrasi yang sesuai berdasarkan tab aktif
        if ($this->activeTab === 'siswa') {
            $this->registerStudent();
        } else {
            $this->registerTeacher();
        }
    }

    /**
     * Logika untuk mendaftarkan siswa.
     */
    private function registerStudent()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
            'class_id' => 'required|exists:classes,id', // Wajib untuk siswa
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'class_id' => $validated['class_id'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('siswa');
        auth()->login($user);
        $this->dispatch('message', [
            'type' => 'success',
            'message' => 'Registrasi berhasil! Selamat datang, ' . $user->name,
        ]);
        return $this->redirect(route('student.dashboard'), navigate: true);
    }

    /**
     * Logika untuk mendaftarkan guru.
     */
    private function registerTeacher()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('guru');
        auth()->login($user);
        $this->dispatch('message', [
            'type' => 'success',
            'message' => 'Registrasi berhasil! Selamat Datang!' . $user->name,
        ]);

        return $this->redirect(route('teacher.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
