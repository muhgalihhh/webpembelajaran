<?php

// =============================================
// STEP 1: Buat file Trait
// File: app/Traits/WithSweetAlert.php
// =============================================

namespace App\Traits;

trait WithSweetAlert
{
    /**
     * Menampilkan alert success
     */
    public function swalSuccess($title, $text = '', $options = [])
    {
        $defaultOptions = [
            'type' => 'success',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK',
            'timer' => 3000,
            'timerProgressBar' => true
        ];

        $this->dispatch('swal:fire', array_merge($defaultOptions, $options));
    }

    /**
     * Menampilkan alert error
     */
    public function swalError($title, $text = '', $options = [])
    {
        $defaultOptions = [
            'type' => 'error',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK'
        ];

        $this->dispatch('swal:fire', array_merge($defaultOptions, $options));
    }

    /**
     * Menampilkan alert warning
     */
    public function swalWarning($title, $text = '', $options = [])
    {
        $defaultOptions = [
            'type' => 'warning',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK'
        ];

        $this->dispatch('swal:fire', array_merge($defaultOptions, $options));
    }

    /**
     * Menampilkan alert info
     */
    public function swalInfo($title, $text = '', $options = [])
    {
        $defaultOptions = [
            'type' => 'info',
            'title' => $title,
            'text' => $text,
            'confirmButtonText' => 'OK'
        ];

        $this->dispatch('swal:fire', array_merge($defaultOptions, $options));
    }

    /**
     * Menampilkan konfirmasi dialog
     */
    public function swalConfirm($title, $text = '', $confirmMethod = null, $params = [], $options = [])
    {
        $defaultOptions = [
            'type' => 'warning',
            'title' => $title,
            'text' => $text,
            'showCancelButton' => true,
            'confirmButtonText' => 'Ya',
            'cancelButtonText' => 'Batal',
            'confirmButtonColor' => '#EF4444',
            'cancelButtonColor' => '#6B7280'
        ];

        $alertOptions = array_merge($defaultOptions, $options);

        // Tambahkan method dan params untuk callback
        if ($confirmMethod) {
            $alertOptions['method'] = $confirmMethod;
            $alertOptions['params'] = $params;
        }

        $this->dispatch('swal:confirm', $alertOptions);
    }

    /**
     * Menampilkan toast notification
     */
    public function swalToast($message, $type = 'success', $options = [])
    {
        $defaultOptions = [
            'type' => $type,
            'message' => $message,
            'position' => 'top-end',
            'timer' => 3000,
            'showConfirmButton' => false,
            'timerProgressBar' => true
        ];

        $this->dispatch('swal:toast', array_merge($defaultOptions, $options));
    }

    /**
     * Menampilkan input dialog
     */
    public function swalInput($title, $inputType = 'text', $callback = null, $options = [])
    {
        $defaultOptions = [
            'title' => $title,
            'input' => $inputType,
            'showCancelButton' => true,
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Batal',
            'inputValidator' => null
        ];

        $alertOptions = array_merge($defaultOptions, $options);

        if ($callback) {
            $alertOptions['callback'] = $callback;
        }

        $this->dispatch('swal:input', $alertOptions);
    }

    /**
     * Custom SweetAlert dengan opsi penuh
     */
    public function swalCustom($options = [])
    {
        $this->dispatch('swal:fire', $options);
    }

    /**
     * Loading alert
     */
    public function swalLoading($title = 'Loading...', $text = 'Mohon tunggu sebentar')
    {
        $this->dispatch('swal:fire', [
            'title' => $title,
            'text' => $text,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'allowEnterKey' => false,
            'showConfirmButton' => false,
            'onOpen' => 'Swal.showLoading()'
        ]);
    }

    /**
     * Close SweetAlert
     */
    public function swalClose()
    {
        $this->dispatch('swal:close');
    }
}
