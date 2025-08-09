<?php

namespace App\Http\Controllers;

use App\Models\ApplyNumber;
use App\Models\IpNumber;
use App\Models\MinuteBundle;
use App\Models\Package;
use App\Models\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiteController extends Controller
{
    public function home(){
        SEOMeta::setTitle('Home');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $faqs = \App\Models\Faq::all();

        if ($faqs->isEmpty()) {
            // No FAQs found — handle accordingly
            $firstHalf = collect();  // empty collection
            $secondHalf = collect(); // empty collection
        } else {
            $count = $faqs->count();
            $half = (int) ceil($count / 2);

            $firstHalf = $faqs->slice(0, $half);
            $secondHalf = $faqs->slice($half);
        }
        $data = [
            'firstHalf' => $firstHalf,
            'secondHalf' => $secondHalf,
            'faqs' => $faqs
        ];
        return view('website.pages.home',$data);
    }
    public function slug($slug)
    {
        $page = \App\Models\Page::where('slug',$slug)->first();
        if (!$page){
            abort(404);
        }
        SEOMeta::setTitle($page->title);
        return view('website.pages.page',compact('page'));
    }
    public function billPay(Request $request)
    {
        SEOMeta::setTitle('Bill Pay');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $data = [

        ];
        return view('website.pages.billPay',$data);
    }
    public function package()
    {
        SEOMeta::setTitle('Packages');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $packages = Package::where('status',true)->where('is_hidden',false)
            ->orderBy('call_rate','asc')
            ->orderBy('price','asc')
            ->get();
        $data = [
            'packages' => $packages,

        ];
        return view('website.pages.packages',$data);
    }
    public function searchNumber(Request $request)
    {
        SEOMeta::setTitle('Search Number');
        SEOMeta::setDescription(getSetting('site_tagline'));

        $query = IpNumber::query();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('price') && $request->price !== 'all') {
            if ($request->price === 'free') {
                $query->where('price', 0);
            } elseif ($request->price === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Search by number
        if ($request->filled('search')) {
            $query->where('number', '=', $request->search);
            //$query->where('number', 'like', '%' . $request->search . '%');
        }

        $ipNumbers = $query

            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('price', 'asc')
            ->get();
        $data = [
            'ipNumbers' => $ipNumbers,

        ];
        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('website.partials.ip_table', compact('ipNumbers'))->render();
        }
        return view('website.pages.searchNumber',$data);
    }
    public function applyNumber()
    {
        SEOMeta::setTitle('Apply Number');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $data = [
            'countries' => \App\Models\Country::where('status',true)->get(),
        ];
        return view('website.pages.applyNumber',$data);
    }
    public function applyNumberSubmit(Request $request)
    {
        // Validate basic required fields (you can expand)
        $request->validate([
            'your_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'account_type' => 'required|in:personal,business',
            'nid_font_image' => 'required|image|max:2048',
            'nid_back_image' => 'required|image|max:2048',
            'selfie_photo' => 'required|image|max:2048',
            'trade_license' => 'nullable|image|max:2048',
        ]);
        // 1. Find user by email or phone
        $user = null;
        if ($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        }
        if (!$user && $request->filled('phone')) {
            $user = User::where('phone', $request->phone)->first();
        }

        // 2. If user not found, create user
        if (!$user) {
            $user = User::create([
                'name' => $request->your_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('defaultpassword'), // or generate random or ask user to reset password
                'phone_country_code' => $request->phone_country_code,
                'whatsapp_country_code' => $request->WhatsApp_country_code,
                'whatsapp_number' => $request->whatsapp_number,
            ]);
        }

        // 3. Store uploaded files and get paths
        $nidFrontPath = $request->file('nid_font_image')->store('apply_numbers/nid_front', 'public');
        $nidBackPath = $request->file('nid_back_image')->store('apply_numbers/nid_back', 'public');
        $selfiePhotoPath = $request->file('selfie_photo')->store('apply_numbers/selfie', 'public');
        $tradeLicensePath = $request->hasFile('trade_license')
            ? $request->file('trade_license')->store('apply_numbers/trade_license', 'public')
            : null;

        // 4. Create ApplyNumber record
        $applyNumber = ApplyNumber::create([
            'user_id' => $user->id,
            'account_type' => $request->account_type,
            'name' => $request->your_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_country_code' => $request->phone_country_code,
            'whatsapp_country_code' => $request->whatsapp_country_code,
            'whatsapp_number' => $request->whatsapp_number,
            'ip_number' => $request->ip_number,
            'enather_ip_number' => $request->enather_ip_number,
            'nid_font_image' => $nidFrontPath,
            'nid_back_image' => $nidBackPath,
            'trade_license' => $tradeLicensePath,
            'selfie_photo' => $selfiePhotoPath,
            'status' => ApplyNumber::STATUS_PENDING, // default status
        ]);

        return redirect(route('thank_you'));
    }
    public function recharge(Request $request)
    {
        SEOMeta::setTitle('Recharge');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $data = [

        ];
        return view('website.pages.recharge',$data);
    }
    public function thankYou()
    {
        SEOMeta::setTitle('Thank you');
        SEOMeta::setDescription(getSetting('site_tagline'));
        return view('website.pages.thankYou');
    }
    public function minuteBundle()
    {
        SEOMeta::setTitle('Minute Bundle');
        SEOMeta::setDescription(getSetting('site_tagline'));
        $bundles = MinuteBundle::where('status',true)
            ->orderBy('minutes','asc')
            ->orderBy('validity','asc')
            ->orderBy('price','asc')
            ->get();
        $data = [
            'bundles' => $bundles,

        ];
        return view('website.pages.minuteBundle',$data);
    }
}
