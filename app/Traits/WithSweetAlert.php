<?php

namespace App\Traits;

trait WithSweetAlert
{
    /**
     * Show success alert
     */
    public function swalSuccess($title, $text = '', $timer = 3000)
    {
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => $title,
            'text' => $text,
            'timer' => $timer,
            'timerProgressBar' => true,
            'showConfirmButton' => false
        ]);
    }


    public function swalError($title, $text = '')
    {
        $this->dispatch('swal', [
            'icon' => 'error',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK',
            'confirmButtonColor' => '#EF4444'
        ]);
    }

    /**
     * Show warning alert
     */
    public function swalWarning($title, $text = '')
    {
        $this->dispatch('swal', [
            'icon' => 'warning',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK',
            'confirmButtonColor' => '#F59E0B'
        ]);
    }

    /**
     * Show info alert
     */
    public function swalInfo($title, $text = '')
    {
        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK',
            'confirmButtonColor' => '#3B82F6'
        ]);
    }

    /**
     * Show confirmation dialog
     */
    public function swalConfirm($title, $text = '', $method = null, $params = [])
    {
        $this->dispatch('swal-confirm', [
            'icon' => 'question',
            'title' => $title,
            'text' => $text,
            'showCancelButton' => true,
            'confirmButtonText' => 'Ya',
            'cancelButtonText' => 'Batal',
            'confirmButtonColor' => '#EF4444',
            'cancelButtonColor' => '#6B7280',
            'method' => $method,
            'params' => $params
        ]);
    }

    /**
     * Show toast notification
     */
    public function swalToast($message, $icon = 'success', $position = 'top-end')
    {
        $this->dispatch('swal-toast', [
            'icon' => $icon,
            'title' => $message,
            'toast' => true,
            'position' => $position,
            'timer' => 3000,
            'timerProgressBar' => true,
            'showConfirmButton' => false
        ]);
    }

    /**
     * Show loading alert
     */
    public function swalLoading($title = 'Loading...', $text = 'Mohon tunggu sebentar')
    {
        $this->dispatch('swal-loading', [
            'title' => $title,
            'text' => $text,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'showConfirmButton' => false,
            'didOpen' => 'showLoading'
        ]);
    }

    /**
     * Close SweetAlert
     */
    public function swalClose()
    {
        $this->dispatch('swal-close');
    }
}
