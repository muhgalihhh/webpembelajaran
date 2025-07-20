<?php

namespace App\Livewire\Auth;

use App\Traits\WithSweetAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class LogoutHandler extends Component
{
    use WithSweetAlert;
    // Method ini akan berjalan ketika event 'perform-logout' diterima
    #[On('perform-logout')]
    public function logout()
    {
        Auth::logout();



        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return $this->redirect('/', navigate: true);
    }

    // Komponen ini tidak merender HTML apapun (headless)
    public function render()
    {
        return <<<'HTML'
        <div>
            {{-- This component is headless and does not render anything --}}
        </div>
        HTML;
    }
}
