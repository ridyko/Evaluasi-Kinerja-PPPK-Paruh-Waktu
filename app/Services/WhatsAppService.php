<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendMessage($to, $message, $evaluasiId = null)
    {
        $url = get_setting('wa_gateway_url') ?: 'http://localhost:3000';
        $token = get_setting('wa_gateway_token') ?: 'your-secret-token';

        if (!$url) {
            Log::warning('WhatsApp Gateway URL not configured.');
            return false;
        }

        // Create initial log
        $waLog = null;
        if ($evaluasiId) {
            $waLog = \App\Models\WaLog::create([
                'evaluasi_id' => $evaluasiId,
                'nomor_tujuan' => $to,
                'pesan' => $message,
                'status' => 'pending'
            ]);
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
                if ($waLog) $waLog->update(['status' => 'sent']);
                return true;
            }

            $errorMsg = 'WhatsApp API Error: ' . $response->body();
            Log::error($errorMsg);
            if ($waLog) $waLog->update(['status' => 'failed', 'keterangan' => $errorMsg]);
            return false;
        } catch (\Exception $e) {
            $errorMsg = 'WhatsApp Connection Error: ' . $e->getMessage();
            Log::error($errorMsg);
            if ($waLog) $waLog->update(['status' => 'failed', 'keterangan' => $errorMsg]);
            return false;
        }
    }
}
