<?php

namespace App\Traits;

trait WithPopup
{
    /**
     * Menampilkan popup sukses custom.
     */
    protected function showSuccessPopup(string $title, string $message, string $buttonLabel = 'Selesai', ?string $redirectUrl = null)
    {
        $this->dispatch('show-manual-popup', [
            'icon' => 'success', // atau 'check'
            'title' => $title,
            'message' => $message,
            'buttonLabel' => $buttonLabel,
            'redirectUrl' => $redirectUrl,
        ]);
    }

    /**
     * Menampilkan popup error custom.
     */
    protected function showErrorPopup(string $title, string $message, string $buttonLabel = 'Tutup')
    {
        $this->dispatch('show-manual-popup', [
            'icon' => 'error',
            'title' => $title,
            'message' => $message,
            'buttonLabel' => $buttonLabel,
            'redirectUrl' => null,
        ]);
    }
}
