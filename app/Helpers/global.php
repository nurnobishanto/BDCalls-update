<?php
use App\Models\Setting;

if (!function_exists('getSetting')) {
    function getSetting(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $setting = Setting::where('key', $key)->first();

        return $cache[$key] = $setting ? $setting->value : $default;
    }
}

if (!function_exists('setSetting')) {
    function setSetting(string $key, mixed $value): bool
    {
        return (bool) Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
