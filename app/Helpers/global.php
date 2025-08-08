<?php
use App\Models\Setting;
use App\Models\Slider;

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
if (!function_exists('sliders')) {
    /**
     * Get all active sliders ordered by sort_order.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function sliders()
    {
        return Slider::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }
}
if (!function_exists('bn_number')) {
    function bn_number($number): string
    {
        $bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯','.'];
        $en_digits = ['0','1','2','3','4','5','6','7','8','9','.'];

        return str_replace($en_digits, $bn_digits, (string) $number);
    }
}
if (!function_exists('bn_to_en_number')) {
    function bn_to_en_number($number)
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($bn, $en, $number);
    }
}
