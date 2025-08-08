<?php

namespace App\Http\Controllers;

use App\Models\MinuteBundle;
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
