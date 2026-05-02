<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_slogan' => 'nullable|string|max:255',
            'organization_footer' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
            'wa_gateway_url' => 'nullable|url|max:255',
            'wa_gateway_token' => 'nullable|string|max:255',
        ]);

        // Handle File Uploads
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => 'storage/' . $path]);
        }

        if ($request->hasFile('app_favicon')) {
            $path = $request->file('app_favicon')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => 'storage/' . $path]);
        }

        // Handle Text Settings
        unset($data['app_logo'], $data['app_favicon']);
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('system_settings');

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }
}
