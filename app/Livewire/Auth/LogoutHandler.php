<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithSweetAlert;

class LogoutHandler extends Component
{
    use WithSweetAlert;

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
        // Show loading
        $this->swalLoading('Logging out...', 'Mohon tunggu sebentar');

        try {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();



            session()->flash('logout_success', [
                'title' => 'Logout Berhasil',
                'message' => 'Terima kasih telah menggunakan aplikasi'
            ]);

            return $this->redirect('/', navigate: true);

        } catch (\Exception $e) {
            $this->swalError('Gagal Logout', 'Terjadi kesalahan saat logout. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.logout-handler');
    }
}
