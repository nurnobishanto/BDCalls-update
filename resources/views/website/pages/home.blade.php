@extends('layouts.master')

@section('content')
    @if(sliders()->count())
        <div class="splide slider" role="group" aria-label="Homepage banners">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach (sliders() as $slide)
                        <li class="splide__slide">
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" class="d-block w-100 h-100">
                                    @endif
                                    {{-- Fallback when no SD image exists --}}
                                    <img class="img-fluid w-100" loading="lazy" style="max-height: 450px"
                                         src="{{ asset('uploads/'.$slide->image)}}" alt="Slide image">
                                    @if ($slide->link)
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <section class="pt-4 pb-3 text-center bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h2 class="section-title mb-2">Cloud IP Telephone & Call Center Solution!</h2>
                    <p class="fs-6">
                        অফিস/ব্যবসা এবং পার্সোনাল ব্যবহারের জন্য সেরা এবং বিশ্বস্ত IPT সল্যুশন ও কল সেন্টার সাপোর্ট
                        নিশ্চিত করি।
                        IPT সল্যুশন ও কল সেন্টারের জন্য আমরাই দিচ্ছি সর্বোচ্চ নিরাপত্তা ও বৈচিত্র্যময় সুবিধা।
                        একমাত্র আমরাই বাংলাদেশে ৮ ফেব্রুয়ারি থেকে ডেডিকেটেড কল সেন্টার চালু করেছি, যা বাংলাদেশের সকল
                        জেলায় যুগোপযোগী ও বৈচিত্র্যময় স্মার্ট সাপোর্ট সার্ভিস প্রদান করে থাকে।
                    </p>
                </div>
            </div>

        </div>
    </section>
    <section class="pt-4 pb-5 position-relative overflow-hidden" style="z-index: 1;">
        <!-- Canvas Background -->
        <div class="particle-canvas-5 position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
            <canvas style="display: block; background: rgba(255, 255, 255, 0);" width="100%" height="100%"></canvas>
        </div>

        <!-- Main Content -->
        <div class="px-md-5">
            <div class="row m-0 p-0 g-4 align-items-stretch">

                <!-- Card Template -->
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="p-3 bg-white rounded h-100 d-flex flex-column justify-content-between"
                         style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);">
                        <div class="heading position-relative" style="z-index: 1; background-color: #045630;">
                            <h6 class="mt-2 text-white">আইপি টেলিফোনের সুবিধাগুলো!</h6>
                        </div>
                        <div class="card-body pt-3" style="font-size:16px; line-height: 35px;">
                            <ul>
                                <li>একটি অফিসের জন্য একটি মাত্র ফোন নাম্বার ব্যবহারের সুবিধা।</li>
                                <li>একটি মাত্র ফোন নাম্বার দিয়ে একসাথে একাধিক ক্লাউড একাউন্ট এর সাথে কথা বলার
                                    ব্যবস্থা।
                                </li>
                                <li>অটোমেটিক কল রিসেপশন করে অন্তর্ভুক্ত জানাবেঃ (IVR)</li>
                                <li>আলাদা আলাদা সময়ে আলাদা আইভিআর বা কল ফরওয়ার্ড করার সুবিধা।</li>
                                <li>টাইমাল প্রতিনিধি এজেন্ট ও একাধিক কল প্রসেসিং এবং এজেন্ট ও কল মনিটরিং।</li>
                                <li>ইনবাউন্ড কল এর ক্ষেত্রে গ্রুপ কল করার ব্যবস্থা।</li>
                                <li>মোবাইল ফোন/এক্সটেনশন নাম্বারে কল ফরওয়ার্ড, ট্রান্সফার ও কনফারেন্স ব্যবস্থ।</li>
                                <li>অটো কল রেকর্ড, কল লগ, ভয়েস মেইল, টাইম ফিল্টার কল এর সুবিধা।</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Repeat 3 more cards with the same structure -->
                <!-- 2 -->
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="p-3 bg-white rounded h-100 d-flex flex-column justify-content-between"
                         style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);">
                        <div class="heading position-relative" style="z-index: 1; background-color: #045630;">
                            <h6 class="mt-2 text-white">কল সেন্টার এর জন্য আইপি টেলিফোন!</h6>
                        </div>
                        <div class="card-body pt-3" style="font-size:16px; line-height: 35px;">
                            <ul>
                                <li>একটি মাত্র ফোন নাম্বার ব্যবহার করে লাইন খুলে গ্রাহকের সেবা নিশ্চিত করা।</li>
                                <li>লাইভ কল মনিটরিং।</li>
                                <li>প্রতিটি স্টাফের কথোপকথন অটো রেকর্ড ও লগ রিপোর্ট।</li>
                                <li>ভয়েসমেইল এর সুবিধা।</li>
                                <li>অফিস বন্ধ নোটিশ সহ আলাদা সময় অনুযায়ী কল অনুকরণ।</li>
                                <li>টাইমাল এজেন্টের মাধ্যমে সেবা প্রদান।</li>
                                <li>অনুপস্থিত থাকলে অন্য এজেন্ট/মোবাইল নাম্বারে কল ফরওয়ার্ড।</li>
                                <li>প্রয়োজনে অন্য নাম্বারে কল ট্রান্সফার সুবিধা।</li>
                                <li>মিস হওয়া কলের রিপোর্ট অটোমেটিক থাকবে।</li>
                                <li>সেবার মান উন্নয়ন ও গ্রাহকের জন্য নির্ভরযোগ্যতা নিশ্চিতকরণ।</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 3 -->
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="p-3 bg-white rounded h-100 d-flex flex-column justify-content-between"
                         style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);">
                        <div class="heading position-relative" style="z-index: 1; background-color: #045630;">
                            <h6 class="mt-2 text-white">অফিস আইপি টেলিফোন ও পিবিএক্স সার্ভিস!</h6>
                        </div>
                        <div class="card-body pt-3" style="font-size:16px; line-height: 35px;">
                            <ul>
                                <li>একটি মাত্র ফোন নাম্বার হতে পারে আপনার অফিসের একটি ব্র্যান্ড!</li>
                                <li>মোবাইল অথবা এক্সটেনশনে ফ্রি কল করার সুবিধা।</li>
                                <li>ছুটির দিনে স্বয়ংক্রিয় নোটিশ শোনানো।</li>
                                <li>অফিস ছাড়াও বাইরে কল রিসিভের ব্যবস্থা।</li>
                                <li>আইপি টেলিফোনে একাধিক এজেন্ট দিয়ে গ্রাহক সেবা নিশ্চিত করুন।</li>
                                <li>গ্রাহকভিত্তিক তথ্য সংরক্ষণের সুবিধা।</li>
                                <li>বিভিন্ন এলাকার জন্য স্থানান্তরের ব্যবস্থা।</li>
                                <li>কল বিজি না হয়ে একাধিক কল গ্রহণযোগ্যতা।</li>
                                <li>IVR ও ভয়েস রেকর্ডিং নির্দেশনা।</li>
                                <li>মোবাইল নাম্বারে ফরওয়ার্ড/ট্রান্সফার/কনফারেন্স কল।</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 4 -->
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="p-3 bg-white rounded h-100 d-flex flex-column justify-content-between"
                         style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);">
                        <div class="heading position-relative" style="z-index: 1; background-color: #045630;">
                            <h6 class="mt-2 text-white">ব্যবসার জন্য আইপি টেলিফোন!</h6>
                        </div>
                        <div class="card-body pt-3" style="font-size:16px; line-height: 35px;">
                            <ul>
                                <li>একটি অফিসের জন্য একটি মাত্র সিঙ্গেল নাম্বার ব্যবহারের সুবিধা।</li>
                                <li>একটি নাম্বার দিয়ে একাধিক ক্লায়েন্টের সাথে কথা বলার ব্যবস্থা।</li>
                                <li>অটোমেটিক কল রিসিভ ও গ্রাহকের জন্য IVR সিস্টেম।</li>
                                <li>ভিন্ন সময় অনুযায়ী ভিন্ন IVR সেটআপ।</li>
                                <li>এজেন্ট সাপোর্ট, কল ট্রান্সফার, ফরওয়ার্ড ও মনিটরিং।</li>
                                <li>রেকর্ডিং, কল লগ, ভয়েস মেইল এবং রিপোর্ট অপশন।</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- stape2 -->
    <div class="pt-4 pb-5 px-md-5" style="background-color: #F5F7FB;">
        <div class="row m-0 p-0 g-4">
            <div class="col-lg-6">
                <div class="grahok rounded " style="background-color: #045630;">
                    <div class="card-body">
                        <div class="d-flex">
                            <img style="height: 50px; width:50px; border-radius: 50%;" src="{{asset('website')}}/img/shield.png" alt="">
                            <div>
                                <div class="ms-2">
                                    <h4 class="text-white">গ্রাহক তথ্যের সর্বোচ্চ নিরাপত্তা</h4>
                                    <p class="text-white" style="font-size:14px;">আমরা প্রদান করছি গ্রাহকের তথ্যের সর্বোচ্চ নিরাপত্তা। কল
                                        রেকর্ড সহ সব ধরনের কল লগ ৯৯.৯৯% নিরাপদ। যেকোন কিছুর বিনিময়ে কোন ভাবেই
                                        হস্তান্তরযোগ্য নয়।</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body mt-2">
                        <div class="d-flex">
                            <img style="height: 50px; width:50px; border-radius: 50%;" src="{{asset('website')}}/img/data-center.png"
                                 alt="">
                            <div>
                                <div class="ms-2">
                                    <h4 class="text-white">ডাটাসেন্টার ও সার্ভার</h4>
                                    <p class="text-white" style="font-size:13px;">আমাদের রয়েছে একাধিক ডেডিকেডেট ডাটা সার্ভার। যা নিশ্চিত
                                        করছে গ্রাহকের তথ্যের সর্বোচ্চ সময় সেবা প্রদান। আমরা ব্যবহার করছি কয়েকটি আলাদা
                                        আইএসপি এর ডেডিকেডেট সংযোগ, যা নিশ্চিত করছে সর্বোচ্চ গ্রাহকের অনলাইনে থাকার
                                        নিশ্চয়তা৷</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body mt-2">
                        <div class="d-flex">
                            <img style="height: 50px; width:50px; border-radius: 50%;" src="{{asset('website')}}/img/package.png" alt="">
                            <div>
                                <div class="ms-2">
                                    <h4 class="text-white">প্যাকেজ কাস্টমাইজেশন সুবিধা</h4>
                                    <p class="text-white" style="font-size:14px;">আমাদের প্রতিটি প্যাকেজ-ই গ্রাহকের চাহিদা মতো কাস্টমাইজ
                                        করা সম্ভব। তাই, গ্রাহক কোন প্রকার হিডেন খরচ ছাড়াই পাচ্ছেন বাজারের সেরা আইপি
                                        টেলিফোনি সুবিধা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2 -->
            <div class="col-lg-6 pt-3">
                <div class="card2 px-3 pb-3 rounded" style="line-height: 20px; background-color: #CFE2FF;">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-6 col-12 col-md-6">
                                <div class="carde p-2">
                                    <div class="card-body text-start">
                                        <div class="d-flex justify-content-center">
                                            <i class="solid fa-solid p-2 fa-phone-volume"></i>
                                            <h6 class="p-2">সেরা কল রেট</h6>
                                        </div>
                                        <p>সাশ্রয়ী মূল্যে দেশের যেকোনো নাম্বারে পরিষ্কার ও নিরবচ্ছিন্ন কল করার সুবিধা।
                                            সহজ রিচার্জ, দীর্ঘ মেয়াদ এবং নির্ভরযোগ্য কানেকশন সহ সেরা কল রেট উপভোগ করুন
                                            আজই!</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12 col-md-6">
                                <div class="carde p-2">
                                    <div class="card-body text-start">
                                        <div class="d-flex justify-content-center">
                                            <i class="solid fa-solid p-2 fa-handshake"></i>
                                            <h6 class="p-2">বিশ্বাসযোগ্য</h6>
                                        </div>
                                        <p>আমারা প্রমাণ করেছি গ্রাহকের ফোন নাম্বারের সর্বোচ্চ নিরাপ সংযোগ চালু অবস্হায়,
                                            কোনোভাবেই নাম্বার বা পরিশেবা হস্তান্তরযোগ্য নয়। যার ফলে ফোন নাম্বার হারিয়ে
                                            যাওয়ার কোনো সম্ভাবনা নেই</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12 col-md-6">
                                <div class="carde p-2">
                                    <div class="card-body text-start">
                                        <div class="d-flex justify-content-center">
                                            <i class="solid fa-solid p-2 fa-baby-carriage"></i>
                                            <h6 class="p-2">সহজ ব্যবহার</h6>
                                        </div>
                                        <p>আমারা প্রমাণ করেছি গ্রাহকের ফোন নাম্বারের সর্বোচ্চ নিরাপ সংযোগ চালু অবস্হায়,
                                            কোনোভাবেই নাম্বার বা পরিশেবা হস্তান্তরযোগ্য নয়। যার ফলে ফোন নাম্বার হারিয়ে
                                            যাওয়ার কোনো সম্ভাবনা নেই</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12 col-md-6">
                                <div class="carde p-2">
                                    <div class="card-body text-start">
                                        <div class="d-flex justify-content-center">
                                            <i class="solid fa-solid p-2 fa-hands"></i>
                                            <h6 class="p-2">এক্সপার্ট সাপোর্ট</h6>
                                        </div>
                                        <p>আমারা প্রমাণ করেছি গ্রাহকের ফোন নাম্বারের সর্বোচ্চ নিরাপ সংযোগ চালু অবস্হায়,
                                            কোনোভাবেই নাম্বার বা পরিশেবা হস্তান্তরযোগ্য নয়। যার ফলে ফোন নাম্বার হারিয়ে
                                            যাওয়ার কোনো সম্ভাবনা নেই</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="animated-section">
        <div class="particle-canvas"></div>

        <div class="container animated-content">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-bold">সাধারণ প্রশ্ন ও সম্ভাব্য উত্তর ❓</h2>
                </div>
            </div>

            <div class="row gx-4 gy-0 gy-md-4">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="accordion" id="accordionLeft">
                        @foreach($firstHalf as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLeft{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseLeft{{ $faq->id }}" aria-expanded="false"
                                            aria-controls="collapseLeft{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseLeft{{ $faq->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingLeft{{ $faq->id }}" data-bs-parent="#accordionLeft">
                                    <div class="accordion-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="accordion" id="accordionRight">
                        @foreach($secondHalf  as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingRight{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseRight{{ $faq->id }}" aria-expanded="false"
                                            aria-controls="collapseRight{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseRight{{ $faq->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingRight{{ $faq->id }}" data-bs-parent="#accordionRight">
                                    <div class="accordion-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section>
        <div class="py-5" style="background-color: #F5F7FB;">
            <h3 class="text-center fw-bold">আমাদের ক্লাইন্ট</h3>
            <p class="text-center col-11 col-md-9 mx-auto fs-6">আমাদের রয়েছে অভিজ্ঞ টিম মেম্বার, যারা সব সময়-ই চেষ্টা করছে গ্রাহক যেন নিরবিচ্ছিন্ন সেবা গ্রহন করতে পারে। তাই আমরা সকল গ্রাহককের সেবার মান নিশ্চিত করতে সব সময় কাজ করে চলেছি</p>
            <div class="container mx-auto row m-0 p-0">
                <div class="col-md-12 m-0 p-0">
                    <div id="testimonial-slider1" class="owl-carousel">
                        @foreach(clients() as $client)
                        <div class="testimonial bg-white me-3">
                            <div class="testimonial-content">
                                <img class="img-fluid" src="{{asset('uploads/'.$client->image)}}" alt="{{$client->name}}">
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_css')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <!-- jParticles -->
    <script src="https://unpkg.com/jparticles/browser/jparticles.base.js"></script>
    <script src="https://unpkg.com/jparticles/browser/particle.js"></script>
    <style>
        .heading {
            background-color: rgb(111, 42, 42);
            border-radius: 23px 0px 23px 0px;
            padding: 4px;
            text-align: center;
            color: white;
        }
        .carde{
            background-color:white;
            border-radius: 10px 10px 10px 10px;
            text-align: center;
        }
        .solid{
            color: rgb(237, 70, 41);
            font-size: 20px;
        }
        .grahok{
            background-color:rgb(237, 70, 41);
            color:white;
            padding: 30px;
        }

        /* Container for FAQ and particles */
        .animated-section {
            position: relative;
            background-color: #f0f8ff;
            overflow: hidden;
        }

        /* Particle canvas wrapper */
        .particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none; /* particles don’t block clicks */
        }

        /* Optional overlay to soften particles */
        .faq-animated::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 1;
            pointer-events: none;
        }

        /* FAQ content on top */
        .animated-content {
            position: relative;
            z-index: 2;
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        /* Ensure accordion button text is dark */
        .accordion-button {
            color: #212529;
            font-weight: 500;
        }

        .scroll-offset {
            scroll-margin-top: 80px; /* Adjust this to your needed top space */
        }
    </style>

    <style>
        .counter-section {
            background: #f8f9fa;
            padding: 60px 15px;
            border-radius: 12px;
        }

        .counter-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
            text-align: center;
            padding: 40px 20px;
            transition: transform 0.3s ease;
            cursor: default;
        }

        .counter-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgb(0 123 255 / 0.25);
        }

        .counter-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd; /* Bootstrap primary blue */
            margin-bottom: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .counter-label {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
        }

        .total-label {
            font-size: 1.5rem;
            font-weight: 700;
            color: #198754;
        }
    </style>
@endsection

@section('custom_js')
    <!-- jParticles init -->
    <script>
        new JParticles.Particle('.particle-canvas', {
            color: '#007bff',
            range: 800,
            maxParticles: 120,
            proximity: 100,
            parallax: true,
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var splide = new Splide('.slider', {
                type: 'loop',
                autoplay: 'play',
                perPage: 1,
                breakpoints: {
                    700: {
                        perPage: 1,
                    },
                },
            });
            splide.mount();

        });

    </script>
    <!-- Include Owl Carousel 2 CSS/JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#testimonial-slider1").owlCarousel({
                items: 6,
                loop: true,
                margin: 0,
                autoplay: true,
                autoplayTimeout: 3000,
                smartSpeed: 1000,
                autoplayHoverPause: true, // eta add korlam
                responsive: {
                    0: { items: 3 },
                    650: { items: 3 },
                    768: { items: 4 },
                    1000: { items: 6 }
                }
            });
        });
    </script>
@endsection
