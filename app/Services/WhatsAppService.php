<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendMessage($to, $message)
    {
        $url = get_setting('wa_gateway_url');
        $token = get_setting('wa_gateway_token');

        if (!$url) {
            Log::warning('WhatsApp Gateway URL not configured.');
            return false;
        }

        // Clean phone number
        $to = preg_replace('/[^0-9]/', '', $to);
        if (str_starts_with($to, '0')) {
            $to = '62' . substr($to, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post($url . '/send-message', [
                'to' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WhatsApp API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp Connection Error: ' . $e->getMessage());
            return false;
        }
    }
}
