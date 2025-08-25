<?php
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;

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
if (!function_exists('clients')) {
    /**
     * Get all active sliders ordered by sort_order.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function clients()
    {
        return \App\Models\Client::where('status', true)
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
if (!function_exists('number_validation')) {
    function number_validation($number): array|bool|string
    {
        $number = str_replace(' ', '', $number);
        $number = str_replace('-', '', $number);

        if (preg_match('/^(\+880|880|0)?1(1|3|4|5|6|7|8|9)\d{8}$/', $number) == 1) {

            if (preg_match("/^\+88/", $number) == 1) {
                $number = str_replace('+', '', $number);
            }
            if (preg_match("/^880|^0/", $number) == 0) {
                $number = "880" . $number;
            }
            if (preg_match("/^88/", $number) == 0) {
                $number = "88" . $number;
            }

            return $number;
        } else {
            return false;
        }
    }
}
if (!function_exists('send_sms')) {
    function send_sms($number, $msg, $type)
    {
        if (getSetting('sms_status',0)){
            $provider = getSetting('sms_provider','bulk_sms_bd');
            if ($provider == 'bulk_sms_bd') {
                $status = bulksmsbd_sms_send($number, $msg);
                return $status;
            }elseif ($provider == 'wa_cloud'){
                $status = wa_cloud_sms_send($number, $msg);
                return $status;
            }
        }

    }
}
if (!function_exists('wa_cloud_sms_send')) {
    function wa_cloud_sms_send(string $phone_number, string $msg, ?string $media_url = null): bool
    {
        $url = "https://api.wacloud.app/send-message";
        $api_key = getSetting('wa_cloud_api');
        $instance_id = getSetting('wa_cloud_instance_id');

        if (!$api_key || !$instance_id) {
            Log::error('WA Cloud SMS: Missing API key or instance ID.');
            return false;
        }

        $payload = [
            'recipient'   => $phone_number,
            'content'     => trim($msg),
            'instance_id' => $instance_id,
        ];

        if (!empty($media_url)) {
            $payload['media_url'] = $media_url;
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "API-Key: $api_key",
                "Content-Type: application/json",
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 10,  // timeout after 10 seconds
        ]);

        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false) {
            Log::error("WA Cloud SMS: cURL error: $curl_error");
            return false;
        }

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("WA Cloud SMS: JSON decode error: " . json_last_error_msg());
            Log::error("WA Cloud SMS: Response: $response");
            return false;
        }

        if ($http_status !== 200) {
            Log::error("WA Cloud SMS: HTTP status code $http_status");
            Log::error("WA Cloud SMS: Response: $response");
            return false;
        }

        if (isset($result['success']) && $result['success'] === true) {
            return true;
        } else {
            $msg = $result['message'] ?? 'Unknown error';
            Log::error("WA Cloud SMS: API error - $msg");
            return false;
        }
    }
}
if (!function_exists('bulksmsbd_sms_send')) {
    function bulksmsbd_sms_send($phone_number, $msg): bool
    {

        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = getSetting('bulk_sms_bd_api');
        $senderid = getSetting('bulk_sms_bd_sender_id');
        $number = number_validation($phone_number);
        $message = trim($msg);

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        if ($data->response_code == 202) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('get_balance_bulksmsbd')) {
    function get_balance_bulksmsbd()
    {
        if (getSetting('bulk_sms_bd_api')) {
            $url = "http://bulksmsbd.net/api/getBalanceApi";
            $api_key = getSetting('bulk_sms_bd_api');
            $data = [
                "api_key" => $api_key
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response);
            if ($data->response_code == 202) {
                return $data->balance;
            } else {
                return $data->error_message;
            }
        } else {
            return 'Enter api key to know balance';
        }

    }
}
if (!function_exists('normalize_phone')) {
    /**
     * Normalize phone number with given country code.
     *
     * @param string $rawPhone
     * @param string $countryCode (without +, e.g., "880" for BD)
     * @return string|null
     */
    function normalize_phone(string $rawPhone, string $countryCode = '880'): ?string
    {
        if ($countryCode=="88"){
            $countryCode = "880";
        }
        // Remove any characters except digits
        $phone = preg_replace('/\D+/', '', $rawPhone);

        // Remove leading zeros
        $phone = ltrim($phone, '0');

        // Remove duplicate country code if exists (e.g. 88088017...)
        if (str_starts_with($phone, $countryCode . $countryCode)) {
            $phone = substr($phone, strlen($countryCode));
        }

        // Remove single occurrence of country code if it's already there
        if (str_starts_with($phone, $countryCode)) {
            $phone = substr($phone, strlen($countryCode));
        }

        // Return final normalized phone

        return $countryCode . $phone;
    }
}
if (!function_exists('netsmsbd_sms_send')) {
    /**
     * Send SMS via NetSMSBD
     *
     * @param string|array $phone_number
     * @param string $msg
     * @return bool
     */
    function netsmsbd_sms_send($phone_number, string $msg): bool
    {
        // Collect API info dynamically
        $url = "https://netsmsbd.com/v1.1/sms";
        $api_key = getSetting('netsms_api_key');
        $senderid = getSetting('netsms_sender_id') ?: 'BDCALLS';

        if (!$api_key || !$phone_number || !$msg || !number_validation($phone_number)) {
            return false;
        }

        // If multiple numbers, convert to comma-separated
        if (is_array($phone_number)) {
            $phone_number = implode(',', number_validation($phone_number));
        }

        $data = [
            "apiKey"   => $api_key,
            "senderId" => $senderid,
            "mobileNo" => $phone_number,
            "msgBody"  => $msg
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

        return isset($data->response_code) && $data->response_code == 202;
    }
}
