<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function index()
    {
        if (config('app.installed')) {
            return redirect('/');
        }
        return view('install.index');
    }

    public function setup(Request $request)
    {
        $request->validate([
            'installer_key' => 'required',
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
        ]);

        if ($request->installer_key !== config('app.installer_key')) {
            return response()->json(['success' => false, 'message' => 'Installer Key tidak valid!'], 403);
        }

        try {
            // 1. Update .env file
            $this->updateEnv([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ]);

            // Clear config cache to use new DB settings
            Artisan::call('config:clear');

            // 2. Check DB Connection
            DB::connection()->getPdo();

            // 3. Run Migrations and Seed
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);

            // 4. Mark as installed
            $this->updateEnv(['APP_INSTALLED' => 'true']);

            return response()->json(['success' => true, 'message' => 'Instalasi berhasil!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    protected function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (!File::exists($path)) {
            File::copy(base_path('.env.example'), $path);
        }

        $content = File::get($path);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        File::put($path, $content);
    }
}
