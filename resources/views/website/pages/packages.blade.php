@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->

    <!-- End Page Title Area -->
    <section class="py-4 py-lg-5">

        <div class="container">
            <h2 class="text-center">উদ্যোক্তা এবং ছোট কোম্পানির জন্য সাশ্রয়ী প্যাকেজ</h2>
            <p class="fs-6 text-center">এটি সম্পুর্ন ইন্টারনেট নির্ভর একটি সার্ভিস। বাংলাদেশের যেকোন প্রান্ত থেকে এই সার্ভিসটি ব্যবহার করা যাবে।</p>
            <div class="row justify-content-center gy-4">
                @foreach($packages as $package)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card-custom pb-3">
                            <div class="p-1" style="background-color: rgb(6, 6, 150); border-top-left-radius: 5px; border-top-right-radius: 5px;">
                                <h5 class="text-center text-white mb-2 fw-bold mt-3">{{$package->name}}</h5>
                            </div>

                            <div class="p-3" style="background-color:rgb(71, 71, 248);">
                                <p class="h6 fw-bold text-white text-center" style="line-height: 25px;"><b class="h4 fw-bold text-white"> মূল্য:
                                    {{bn_number(number_format($package->price))}} </b> টাকা/প্রতিমাস</p>
                            </div>

                            <ul class="feature-list px-3 pt-3">
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>নাম্বার নিবন্ধন: {{$package->registration_number}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ব্যবহারকারী: {{$package->user}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চ্যানেল: {{$package->call_channel}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চ্যানেল চার্জ: {{$package->call_channel_charges}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>অতিরিক্ত এক্সটেনশন: {{$package->additional_extensions}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>আইভিআর সাপোর্ট: {{$package->ivr_support}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ওয়েব স্পেস: {{$package->web_space}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>RAM: {{$package->ram}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল রেকর্ড: {{$package->call_record}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ভয়েস মেইল: {{$package->voice_mail}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল ফরওয়ার্ড:{{$package->call_forward}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল ট্রান্সফার: {{$package->call_transfer}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ডাটা ব্যাকআপ: {{$package->data_backup}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>রিকভারি/রিস্টোর: {{$package->recovery}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>রিং গ্রুপ: {{$package->ring_group}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>নাম্বার ব্ল্যাকলিস্ট: {{$package->amber_blacklist}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চার্জ (মোবাইল & TNT): {{$package->call_charge_mobile_tnt}} </li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>পালস: {{$package->pulse}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চার্জ (আইভিআর নাম্বার): {{$package->call_charges_ivr_number}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চার্জ (নিজস্ব নেটওয়ার্ক): {{$package->call_charges_own_network}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ইনকামিং চার্জ: {{$package->incoming_charges}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>সাপোর্টেড ডিভাইস: {{$package->supported_devices}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>স্প্যাম ফিল্টার: {{$package->spam_filter}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>সংযোগ ধরন: {{$package->connection_type}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>সংযোগ পদ্ধতি: {{$package->connection_method}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কাস্টম কনফিগারেশন: {{$package->custom_configuration}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>সংযোগ চার্জ: {{$package->connection_charges}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>আপটাইম গ্যারান্টি: {{$package->uptime_guarantee}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কন্ট্রোল প্যানেল: {{$package->control_panel}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>একাউন্ট অ-ব্যবহৃত অবস্থায় সক্রিয় থাকবে: {{$package->account_will_remain_day}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>স্বয়ংক্রিয় টার্মিনেশন (অব্যবহৃত): {{$package->automatic_termination_day}}</li>

                            </ul>
                            <div class="d-flex justify-content-center w-100 pt-2">
                                <button type="button" class="btn-cancel fw-bold order-btn">অর্ডার করুন</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('custom_css')
    <style>
        .card-custom {
            background-color: white;
            border-radius: 5px;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Gradient border effect */
        .card-custom::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 5px;
            padding: 1px; /* Thickness of the "border" */
            mask-composite: exclude;
            pointer-events: none;
        }
        .feature-list {
            font-size: 14px;
            padding-left: 0;
            list-style: none;

        }
        .feature-list li {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            color: #4a4a4a;
        }
        .feature-list li.unchecked {
            color: #a3a3a3;
            text-decoration: line-through;
        }
        .feature-list li .icon {
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            font-size: 14px;
        }
        .feature-list li.checked .icon {
            background-color: #a3c85a;
            color: white;
            border: none;
        }
        .feature-list li.unchecked .icon {
            border: 1px solid #a3a3a3;
            color: #a3a3a3;
            background: transparent;
        }
        .btn-cancel {
            background-color: rgb(71, 71, 248) !important;
            color: white;
            font-size: 14px;
            font-weight: 400;
            border-radius: 3px;
            padding: 0.5rem 2rem;
            border: none;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            transition: background-color 0.3s ease;
        }
        .btn-cancel:hover {
            background-color: rgba(255 255 255 / 1);
        }
    </style>
@endsection
@section('custom_js')
@endsection
