<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithSweetAlert; // Pastikan trait ini digunakan

class LogoutHandler extends Component
{
    use WithSweetAlert; // Pastikan baris ini ada untuk mengakses fungsi swal*

    // Definisi listener untuk menangkap event 'show-logout-confirmation'
    // yang akan dikirim dari frontend JavaScript.
    protected $listeners = [
        'show-logout-confirmation' => 'performLogoutAndRedirect',
    ];

    /**
     * Metode ini akan dipanggil oleh event Livewire dari frontend.
     * Bertanggung jawab untuk proses logout, menampilkan SweetAlert sukses,
     * dan kemudian memicu redirect dengan penundaan.
     */
    public function performLogoutAndRedirect()
    {

        $this->swalClose();

        try {

            Auth::logout();
            request()->session()->invalidate(); // Invalidasi session pengguna
            request()->session()->regenerateToken(); // Regenerate token CSRF

            $this->swalSuccess('Logout Berhasil', 'Terima kasih telah menggunakan aplikasi');
            $this->dispatch('redirect-after-swal', url: '/');

        } catch (\Exception $e) {

            $this->swalError('Gagal Logout', 'Terjadi kesalahan saat logout. Silakan coba lagi.');
            // Anda mungkin juga ingin log $e->getMessage() untuk debugging.
        }
    }

    /**
     * Metode render untuk view komponen Livewire.
     * Pastikan view ini ada (resources/views/livewire/auth/logout-handler.blade.php)
     * meskipun mungkin kosong jika komponen ini hanya untuk logika backend.
     */
    public function render()
    {
        return view('livewire.auth.logout-handler');
    }
}
