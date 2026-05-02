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
        $logs = \App\Models\WaLog::with('evaluasi.pegawai')->latest()->take(10)->get();
        return view('wa.index', compact('logs'));
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
        // Matikan dulu jika ada yang jalan
        exec("pkill -f 'node index.js'");
        sleep(1);

        $nodePath = shell_exec('which node') ? trim(shell_exec('which node')) : '/usr/local/bin/node';
        $token = get_setting('wa_gateway_token') ?: 'your-secret-token';
        $logPath = $this->gatewayPath . '/output.log';
        
        // Gunakan path absolut untuk semuanya
        $cmd = "cd " . escapeshellarg($this->gatewayPath) . " && WA_TOKEN=" . escapeshellarg($token) . " /usr/bin/nohup {$nodePath} index.js > " . escapeshellarg($logPath) . " 2>&1 &";
        
        shell_exec($cmd);

        // Beri waktu sebentar lalu cek apakah log tercipta
        sleep(2);
        
        if (!file_exists($logPath)) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memulai layanan. Log tidak tercipta.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Layanan sedang diaktifkan...',
        ]);
    }

    public function stop()
    {
        // Matikan semua proses node yang menjalankan index.js di folder wa-gateway
        exec("pkill -f 'node index.js'");
        
        // Hapus folder auth jika ingin reset total (opsional)
        // exec("rm -rf " . escapeshellarg($this->gatewayPath . '/auth_info_baileys'));

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
        // Cek apakah ada proses node index.js yang berjalan
        $output = shell_exec("ps aux | grep 'node index.js' | grep -v grep");
        return !empty(trim($output));
    }
}
