<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class WhatsAppController extends Controller
{
    protected $gatewayPath;

    public function __construct()
    {
        $this->gatewayPath = base_path('wa-gateway');
    }

    public function index()
    {
        return view('wa.index');
    }

    public function status()
    {
        $url = get_setting('wa_gateway_url', 'http://localhost:3000');
        
        try {
            $response = Http::timeout(2)->get($url . '/status');
            if ($response->successful()) {
                return response()->json([
                    'running' => true,
                    'connected' => $response->json('connected'),
                ]);
            }
        } catch (\Exception $e) {
            // Service not running
        }

        return response()->json([
            'running' => false,
            'connected' => false,
        ]);
    }

    public function start()
    {
        if ($this->isRunning()) {
            return response()->json(['status' => false, 'message' => 'Layanan sudah berjalan.']);
        }

        // Jalankan node di background
        $cmd = "cd " . escapeshellarg($this->gatewayPath) . " && nohup node index.js > output.log 2>&1 & echo $!";
        $pid = shell_exec($cmd);

        return response()->json([
            'status' => true,
            'message' => 'Layanan sedang diaktifkan...',
            'pid' => trim($pid)
        ]);
    }

    public function stop()
    {
        // Matikan proses node yang menjalankan index.js di folder wa-gateway
        $cmd = "pkill -f 'node index.js'";
        shell_exec($cmd);

        return response()->json(['status' => true, 'message' => 'Layanan dihentikan.']);
    }

    public function install()
    {
        $cmd = "cd " . escapeshellarg($this->gatewayPath) . " && npm install";
        shell_exec($cmd);

        return response()->json(['status' => true, 'message' => 'Dependensi sedang diinstal...']);
    }

    public function qr()
    {
        $url = get_setting('wa_gateway_url', 'http://localhost:3000');
        try {
            $response = Http::get($url . '/qr');
            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil QR Code.']);
        }
    }

    private function isRunning()
    {
        $output = shell_exec("pgrep -f 'node index.js'");
        return !empty(trim($output));
    }
}
