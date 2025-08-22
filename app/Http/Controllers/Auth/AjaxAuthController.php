<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class AjaxAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'লগইন তথ্য অবশ্যই প্রদান করতে হবে।',
            'password.required' => 'পাসওয়ার্ড অবশ্যই প্রদান করতে হবে।',
        ]);

        // ২. ইনপুট সংগ্রহ
        $login = $request->input('login');
        $password = $request->input('password');

        // ৩. টাইপ চিহ্নিত করা
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $type = 'email';
        } elseif (number_validation(bn_to_en_number($login))) {
            $type = 'phone';
            $login = number_validation(bn_to_en_number($login));
        } else {
            $type = 'username';
            $login = bn_to_en_number($login);
        }

        // ৪. ইউজার খোঁজা
        $user = User::where($type, $login)->first();

        if (!$user) {
            return response()->json([
                'notfound' => true,
                'type' => $type,
                'value' => $login,
                'login' => $request->input('login'),
                'title' => 'ইউজার পাওয়া যায়নি',
                'message' => 'প্রদত্ত তথ্যের সাথে কোনো একাউন্ট মেলেনি। অনুগ্রহ করে সঠিক তথ্য দিয়ে আবার চেষ্টা করুন।',
            ], 422);
        }

        // ৫. পাসওয়ার্ড যাচাই
        if (!password_verify($password, $user->password)) {
            return response()->json([
                'wrongPassword' => true,
                'title' => 'ভুল পাসওয়ার্ড',
                'message' => 'আপনার প্রদত্ত পাসওয়ার্ডটি সঠিক নয়। অনুগ্রহ করে আবার চেষ্টা করুন।',
            ], 422);
        }


        // ৬. লগইন করা ও সেশন রিজেনারেট
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        // ৭. সফল লগইন রেসপন্স
        return response()->json([
            'message' => 'সফলভাবে লগইন সম্পন্ন হয়েছে।',
            'redirect' => session()->get('url.intended', url('/')),
        ]);
    }
    public function register(Request $request)
    {
        // ১. ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'register' => 'required|string|max:255|unique:users,email|unique:users,phone',
            'password' => 'required|string|min:6',
            'college' => 'nullable|string|max:255',
        ], [
            'name.required' => 'নাম অবশ্যই দিতে হবে।',
            'name.string' => 'নাম অবশ্যই সঠিকভাবে লিখতে হবে।',
            'name.max' => 'নাম ২৫৫ অক্ষরের বেশি হতে পারবে না।',

            'register.required' => 'ফোন নম্বর বা ইমেইল অবশ্যই দিতে হবে।',
            'register.string' => 'ফোন নম্বর বা ইমেইল অবশ্যই সঠিকভাবে লিখতে হবে।',
            'register.max' => 'ফোন নম্বর বা ইমেইল ২৫৫ অক্ষরের বেশি হতে পারবে না।',
            'register.unique' => 'এই ইমেইল বা ফোন নম্বরটি ইতিমধ্যেই ব্যবহৃত হয়েছে।',

            'password.required' => 'পাসওয়ার্ড অবশ্যই দিতে হবে।',
            'password.string' => 'পাসওয়ার্ড অবশ্যই সঠিকভাবে লিখতে হবে।',
            'password.min' => 'পাসওয়ার্ড অন্তত ৬ অক্ষরের হতে হবে।',

            'college.string' => 'কলেজের নাম অবশ্যই সঠিকভাবে লিখতে হবে।',
            'college.max' => 'কলেজের নাম ২৫৫ অক্ষরের বেশি হতে পারবে না।',
        ]);

        // ২. ভুল ইনপুটের জন্য রেসপন্স
        if ($validator->fails()) {
            return response()->json([
                'title' => 'ভুল তথ্য',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ৩. লগইন ফিল্ড চিহ্নিতকরণ ও কনভার্সন
        $loginField = filter_var($request->register, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if ($loginField === 'phone') {
            $loginValue = number_validation(bn_to_en_number($request->register));
            if (!$loginValue) {
                return response()->json([
                    'title' => 'ভুল ফোন নম্বর',
                    'message' => 'ফোন নম্বরটি সঠিক নয় বা ফরম্যাট ভুল হয়েছে।',
                ], 422);
            }
        } else {
            $loginValue = $request->register;
        }
        // ৪. ডুপ্লিকেট চেক
        if (User::where($loginField, $loginValue)->exists()) {
            return response()->json([
                'title' => 'অ্যাকাউন্ট ইতিমধ্যেই আছে',
                'message' => 'এই তথ্য দিয়ে ইতিমধ্যেই একটি অ্যাকাউন্ট খোলা হয়েছে।',
            ], 422);
        }

        // ৫. ইউজার তৈরি
        $user = User::create([
            'name' => $request->name,
            $loginField => $loginValue,
            'password' => Hash::make($request->password),
        ]);

        // ৬. ইভেন্ট ও লগইন
        event(new Registered($user));
        Auth::guard('web')->login($user);

        // ৭. সফল রেসপন্স
        return response()->json([
            'message' => 'সফলভাবে নিবন্ধন সম্পন্ন হয়েছে।',
            'redirect' => session()->get('url.intended', url('/')),
        ]);
    }
    public function resetPasswordSendOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
        ], [
            'login.required' => 'লগইন তথ্য অবশ্যই দিতে হবে।', // বাংলা মেসেজ
        ]);

        $login = $request->input('login');
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : (number_validation(bn_to_en_number($login)) ?   'phone':'username');
        if ($type === 'phone'){
            $login = number_validation(bn_to_en_number($login)); // helper
        }elseif ($type === 'username'){
            $login = bn_to_en_number($login);
        }


        $user = User::where($type, $login)->first();
        if (!$user) return response()->json(['message' => 'একাউন্ট খুঁজে পাওয়া যায়নি'], 429);

        $key = "otp_{$type}_{$login}";
        $counterKey = "{$key}_count";
        $lastSentKey = "{$key}_last";

        if (Cache::get($counterKey, 0) >= 3) {
            return response()->json(['message' => 'আপনি আজ ৩ বার OTP পাঠিয়েছেন'], 429);
        }

        if (Cache::has($lastSentKey)) {
            $lastSentAt = Cache::get($lastSentKey);
            $elapsed = now()->diffInSeconds($lastSentAt);
            $wait = 120 + $elapsed;

            if ($wait > 0) {
                $minutes = floor($wait / 60);
                $seconds = $wait % 60;

                $timeText = '';
                if ($minutes > 0) {
                    $timeText .= "{$minutes} মিনিট ";
                }
                if ($seconds > 0) {
                    $timeText .= "{$seconds} সেকেন্ড";
                }

                return response()->json([
                    'message' => "অনুগ্রহ করে {$timeText} পর আবার চেষ্টা করুন"
                ], 429);
            }
        }




        $otp = rand(100000, 999999);
        Log::info($otp);
        Cache::put($key, ['otp' => $otp, 'username' => $user->id], now()->addMinutes(10));
        Cache::put($counterKey, Cache::get($counterKey, 0) + 1, now()->endOfDay());
        Cache::put($lastSentKey, now(), now()->addMinutes(2));

        // For email or SMS
        // Mail::to($user->email)->send(new YourOtpMail($otp));

        $validatedPhone = number_validation($user->phone);
        if ($validatedPhone) {
            send_sms($validatedPhone, 'Your OTP for Hasnat Bangla is: ' . $otp, 'OTP');
        }


        return response()->json(['message' => 'OTP পাঠানো হয়েছে', 'step' => 2]);
    }
    public function resetPasswordVerifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6',
        ], [
            'login.required' => 'লগইন তথ্য অবশ্যই দিতে হবে।',
            'otp.required' => 'ওটিপি অবশ্যই দিতে হবে।',
            'otp.digits' => 'ওটিপি অবশ্যই ৬ অঙ্কের হতে হবে।',
            'password.required' => 'পাসওয়ার্ড অবশ্যই দিতে হবে।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);


        $login = $request->input('login');
        $otp = $request->input('otp');

        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : (number_validation(bn_to_en_number($login)) ?   'phone':'username');
        if ($type === 'phone'){
            $login = number_validation(bn_to_en_number($login)); // helper
        }elseif ($type === 'username'){
            $login = bn_to_en_number($login);
        }

        $key = "otp_{$type}_{$login}";
        $data = Cache::get($key);

        if (!$data || $data['otp'] != $otp) {
            return response()->json(['message' => 'ভুল OTP'], 422);
        }

        $user = User::find($data['username']);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        Cache::forget($key);

        return response()->json(['reset' => true, 'message' => 'পাসওয়ার্ড সফলভাবে রিসেট হয়েছে']);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
        ], [
            'login.required' => 'লগইন তথ্য অবশ্যই দিতে হবে।',
        ]);


        $login = $request->input('login');

        // Here you find user by email, phone, or username (implement your logic)
        $user = \App\Models\User::where('email', $login)
            ->orWhere('phone', number_validation(bn_to_en_number($login)))
            ->orWhere('username', bn_to_en_number($login))
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'প্রদত্ত তথ্যের সাথে কোনো ব্যবহারকারী পাওয়া যায়নি।'
            ], 422);
        }


        $lastSentKey = "otp_last_sent_{$user->id}";
        $countSentKey = "otp_sent_count_{$user->id}";

        // Limit resend to 3 times per day
        $sentCount = Cache::get($countSentKey, 0);
        if ($sentCount >= 3) {
            return response()->json([
                'message' => 'আজ আর ওটিপি পাঠানো যাবে না।'
            ], 429);
        }


        if (Cache::has($lastSentKey)) {
            $lastSentAt = Cache::get($lastSentKey);
            $elapsed = now()->diffInSeconds($lastSentAt);
            $wait = 120 + $elapsed;

            if ($wait > 0) {
                $minutes = floor($wait / 60);
                $seconds = $wait % 60;

                $timeText = '';
                if ($minutes > 0) {
                    $timeText .= "{$minutes} মিনিট ";
                }
                if ($seconds > 0) {
                    $timeText .= "{$seconds} সেকেন্ড";
                }

                return response()->json([
                    'message' => "অনুগ্রহ করে {$timeText} পর আবার চেষ্টা করুন"
                ], 429);
            }
        }

        // Generate OTP and store in cache (5 min expiry)
        $otp = rand(100000, 999999);
        Log::info($otp);
        Cache::put("otp_{$user->id}", $otp, now()->addMinutes(5));
        Cache::put($lastSentKey, now(), 120);  // cooldown 2 mins
        Cache::put($countSentKey, $sentCount + 1, now()->endOfDay());

        $validatedPhone = number_validation($user->phone);
        if ($validatedPhone) {
            send_sms($validatedPhone, 'Your OTP for Hasnat Bangla is: ' . $otp, 'OTP');
        }

        return response()->json([
            'message' => 'ওটিপি পাঠানো হয়েছে',
            'otp' => $otp
        ]);

    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'otp' => 'required|digits:6',
        ], [
            'login.required' => 'লগইন তথ্য অবশ্যই দিতে হবে।',
            'otp.required' => 'ওটিপি অবশ্যই দিতে হবে।',
            'otp.digits' => 'ওটিপি অবশ্যই ৬ অংকের হতে হবে।',
        ]);


        $login = $request->input('login');
        $otp = $request->input('otp');

        $user = \App\Models\User::where('email', $login)
            ->orWhere('phone', number_validation(bn_to_en_number($login)))
            ->orWhere('username', bn_to_en_number($login))
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'প্রদত্ত তথ্য অনুযায়ী কোনো ব্যবহারকারী পাওয়া যায়নি।'
            ], 422);
        }



        $cachedOtp = Cache::get("otp_{$user->id}");
        if (!$cachedOtp || $cachedOtp != $otp) {
            return response()->json([
                'message' => 'ওটিপি ভুল অথবা মেয়াদ উত্তীর্ণ।'
            ], 422);
        }


        // OTP is valid, login user
        Auth::guard('web')->login($user);
        Cache::forget("otp_{$user->id}");

        return response()->json([
            'message' => 'লগইন সফল হয়েছে',
            'redirect' => session()->get('url.intended', url('/')) // প্রয়োজনে রিডাইরেক্ট URL পরিবর্তন করুন
        ]);
    }
}
