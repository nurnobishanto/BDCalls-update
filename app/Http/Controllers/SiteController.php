<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

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
        $data = [
            'firstHalf' => null,

        ];
        return view('website.pages.minuteBundle',$data);
    }
}
