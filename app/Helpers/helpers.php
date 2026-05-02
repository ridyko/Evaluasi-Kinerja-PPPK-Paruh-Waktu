<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $settings = Cache::rememberForever('system_settings', function () {
            return Setting::pluck('value', 'key')->all();
        });

        return $settings[$key] ?? config('app.' . $key, $default);
    }
}
