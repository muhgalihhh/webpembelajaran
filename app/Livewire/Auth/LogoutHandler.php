<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithSweetAlert; // Ensure this trait is used

class LogoutHandler extends Component
{
    use WithSweetAlert; // Make sure this line is present

    protected $listeners = ['confirmLogout'];

    public function showLogoutConfirmation()
    {
        $this->swalConfirm(
            'Konfirmasi Logout',
            'Apakah Anda yakin ingin keluar dari aplikasi?',
            'confirmLogout'
        );
    }

    public function confirmLogout()
    {
        // First, close any existing loading alert if it's still open
        $this->swalClose();
        $this->swalSuccess('Logout Berhasil', 'Terima kasih telah menggunakan aplikasi');

        try {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();



            $this->dispatch('redirect-after-swal', url: '/');

        } catch (\Exception $e) {
            $this->swalError('Gagal Logout', 'Terjadi kesalahan saat logout. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.logout-handler');
    }
}
