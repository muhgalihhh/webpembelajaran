<?php

namespace App\Services;

class SweetAlertService
{
    public static function success($title, $text = '', $options = [])
    {
        return self::fire('success', $title, $text, $options);
    }

    public static function error($title, $text = '', $options = [])
    {
        return self::fire('error', $title, $text, $options);
    }

    public static function warning($title, $text = '', $options = [])
    {
        return self::fire('warning', $title, $text, $options);
    }

    public static function info($title, $text = '', $options = [])
    {
        return self::fire('info', $title, $text, $options);
    }

    public static function question($title, $text = '', $options = [])
    {
        return self::fire('question', $title, $text, $options);
    }

    public static function confirm($title, $text = '', $confirmMethod = null, $params = [], $options = [])
    {
        return [
            'event' => 'swal:confirm',
            'data' => array_merge([
                'type' => 'warning',
                'title' => $title,
                'text' => $text,
                'showCancelButton' => true,
                'confirmButtonText' => 'Ya',
                'cancelButtonText' => 'Batal',
                'method' => $confirmMethod,
                'params' => $params
            ], $options)
        ];
    }

    public static function toast($message, $type = 'success', $options = [])
    {
        return [
            'event' => 'swal:toast',
            'data' => array_merge([
                'type' => $type,
                'message' => $message,
                'timer' => 3000,
                'position' => 'top-end'
            ], $options)
        ];
    }

    public static function input($title, $inputType = 'text', $options = [])
    {
        return [
            'event' => 'swal:input',
            'data' => array_merge([
                'title' => $title,
                'input' => $inputType,
                'showCancelButton' => true,
                'confirmButtonText' => 'Submit',
                'cancelButtonText' => 'Batal'
            ], $options)
        ];
    }

    public static function custom($options = [])
    {
        return [
            'event' => 'swal:fire',
            'data' => $options
        ];
    }

    private static function fire($type, $title, $text, $options)
    {
        return [
            'event' => 'swal:fire',
            'data' => array_merge([
                'type' => $type,
                'title' => $title,
                'text' => $text,
                'confirmButtonText' => 'OK'
            ], $options)
        ];
    }
}

// Trait untuk komponen Livewire
namespace App\Traits;

use App\Services\SweetAlertService;

trait WithSweetAlert
{
    public function swalSuccess($title, $text = '', $options = [])
    {
        $alert = SweetAlertService::success($title, $text, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalError($title, $text = '', $options = [])
    {
        $alert = SweetAlertService::error($title, $text, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalWarning($title, $text = '', $options = [])
    {
        $alert = SweetAlertService::warning($title, $text, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalInfo($title, $text = '', $options = [])
    {
        $alert = SweetAlertService::info($title, $text, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalConfirm($title, $text = '', $confirmMethod = null, $params = [], $options = [])
    {
        $alert = SweetAlertService::confirm($title, $text, $confirmMethod, $params, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalToast($message, $type = 'success', $options = [])
    {
        $alert = SweetAlertService::toast($message, $type, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalInput($title, $inputType = 'text', $options = [])
    {
        $alert = SweetAlertService::input($title, $inputType, $options);
        $this->dispatch($alert['event'], $alert['data']);
    }

    public function swalCustom($options = [])
    {
        $alert = SweetAlertService::custom($options);
        $this->dispatch($alert['event'], $alert['data']);
    }
}
