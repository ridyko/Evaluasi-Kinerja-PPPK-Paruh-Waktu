<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class WhatsAppController extends Controller
{
    protected $gatewayPath;
    protected $isWindows;

    public function __construct()
    {
        $this->gatewayPath = base_path('wa-gateway');
        $this->isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
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
        $token = get_setting('wa_gateway_token') ?: 'your-secret-token';
        
        if ($this->isWindows) {
            // LOGIKA WINDOWS
            exec("taskkill /F /IM node.exe /T 2>nul");
            sleep(1);

            // Coba cari node otomatis atau gunakan path laragon jika ada
            $nodePath = 'node';
            if (file_exists('C:\\laragon\\bin\\nodejs\\node-v22\\node.exe')) {
                $nodePath = 'C:\\laragon\\bin\\nodejs\\node-v22\\node.exe';
            }

            $cdPath = str_replace('/', '\\', $this->gatewayPath);
            $logPath = $cdPath . '\\output.log';
            
            $cmd = "cd /d \"" . $cdPath . "\" && set WA_TOKEN=" . $token . " && start /B \"\" " . $nodePath . " index.js > \"" . $logPath . "\" 2>&1";
            pclose(popen($cmd, "r"));
        } else {
            // LOGIKA MACOS / LINUX
            exec("pkill -f 'node index.js' > /dev/null 2>&1");
            sleep(1);

            $nodePath = shell_exec('which node') ? trim(shell_exec('which node')) : '/usr/local/bin/node';
            $logPath = $this->gatewayPath . '/output.log';
            
            $cmd = "cd " . escapeshellarg($this->gatewayPath) . " && WA_TOKEN=" . escapeshellarg($token) . " /usr/bin/nohup {$nodePath} index.js > " . escapeshellarg($logPath) . " 2>&1 &";
            shell_exec($cmd);
        }

        return response()->json([
            'status' => true,
            'message' => 'Layanan sedang diaktifkan (' . (PHP_OS) . ')...',
        ]);
    }

    public function stop()
    {
        if ($this->isWindows) {
            exec("taskkill /F /IM node.exe /T 2>nul");
        } else {
            exec("pkill -f 'node index.js' > /dev/null 2>&1");
            exec("lsof -t -i:3000 | xargs kill -9 > /dev/null 2>&1");
        }
        
        return response()->json(['status' => true, 'message' => 'Layanan dihentikan.']);
    }

    public function reset()
    {
        $this->stop();
        sleep(1);
        
        if ($this->isWindows) {
            $authPath = str_replace('/', '\\', $this->gatewayPath . '/auth_info_baileys');
            if (file_exists($authPath)) {
                exec("rd /s /q \"" . $authPath . "\"");
            }
        } else {
            exec("rm -rf " . escapeshellarg($this->gatewayPath . '/auth_info_baileys'));
        }

        return response()->json(['status' => true, 'message' => 'Sesi berhasil direset.']);
    }

    public function install()
    {
        if ($this->isWindows) {
            $cdPath = str_replace('/', '\\', $this->gatewayPath);
            $cmd = "cd /d \"" . $cdPath . "\" && npm install";
        } else {
            $cmd = "cd " . escapeshellarg($this->gatewayPath) . " && npm install";
        }
        
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
}
