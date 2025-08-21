@extends('layouts.master')

@section('content')
    <!-- End Page Title Area -->
    <section>
        <div class="container">
            <div class="text-center mb-3 mt-4 px-3">
                <p class="h3 fw-bold">Choose Your Best Bundle </p>
            </div>
            <div class="row gy-3 justify-content-center">
                @foreach($bundles as $bundle)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card-custom mb-4 pb-1">
                            <div class="p-1" style="background-color: rgb(6, 6, 150); border-top-left-radius: 5px; border-top-right-radius: 5px;">
                                <h5 class="text-center text-white mb-2 fw-bold mt-3">{{$bundle->title}}
                                    ({{bn_number($bundle->minutes)}} মিনিট)</h5>
                            </div>
                            <div class="p-3" style="background-color:rgb(71, 71, 248);">
                                <p class="h6 fw-bold text-white text-center" style="line-height: 25px;"><b class="h4 fw-bold text-white"> মূল্য:
                                        {{bn_number(number_format($bundle->price))}} </b> টাকা</p>
                            </div>
                            <ul class="feature-list px-3 pt-3">
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>ইনকামিং
                                    চার্জ: {{$bundle->incoming_charge}}
                                </li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চার্জ আইপি
                                    নাম্বার: {{$bundle->ip_number_charge}}
                                </li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>কল চার্জ
                                    এক্সটেনশন: {{$bundle->extension_charge}}
                                </li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>আউটগোয়িং কল চার্জ
                                    : {{$bundle->outgoing_call_charge}}
                                </li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>পান্স
                                    : {{$bundle->pulse}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>মিনিট
                                    : {{bn_number($bundle->minutes)}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span>মেয়াদ
                                    : {{$bundle->validity}}</li>
                                <li class="checked"><span class="icon"><i class="fas fa-check"></i></span><span
                                        style="font-size: 17px; margin-right: 3px;">{{bn_number(number_format($bundle->price))}} </span>
                                    টাকা
                                </li>
                            </ul>

                            <div class="d-flex justify-content-center mb-3">
                                <button type="button" class="btn btn-primary" >অর্ডার করুন
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
    <!-- Modal -->
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

        .card-custom::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 5px;
            padding: 1px;
            background: linear-gradient(to top, rgba(0, 0, 255, 0.4), rgba(0, 0, 255, 0.3), rgba(0, 0, 255, 0.1));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }



        .feature-list {
            font-size: 14px;
            padding-left: 0;
            list-style: none;
            margin-bottom: 2rem;
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
    </style>



@endsection
@section('custom_js')

@endsection

