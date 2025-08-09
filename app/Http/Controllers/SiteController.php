<?php

namespace App\Http\Controllers;

use App\Models\IpNumber;
use App\Models\MinuteBundle;
use App\Models\Package;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
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
            $query->where('number', 'like', '%' . $request->search . '%');
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
