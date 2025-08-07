<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $token;
    protected $endpoint = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Mengirim pesan ke target (nomor atau ID grup).
     *
     * @param string $target
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $target, string $message): bool
    {

        if (!$this->token) {
            Log::error('Fonnte API token is not set.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->endpoint, [
                        'target' => $target,
                        'message' => $message,
                        'countryCode' => '62', // Opsional: Atur kode negara default
                    ]);

            if (!$response->successful()) {
                Log::error('Failed to send WhatsApp message via Fonnte.', $response->json());
                return false;
            }

            return true;

        } catch (\Exception $e) {
            // Catat error jika terjadi masalah koneksi atau lainnya
            Log::error('Exception when sending WhatsApp message: ' . $e->getMessage());
            return false;
        }
    }
}