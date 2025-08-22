@extends('layouts.master')

@section('content')

    <section class="py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary">
                            <h6 class="text-light small text-center">Invoice #{{ $payment->order?->invoice_no }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-2">
                                <div class="amount-display">
                                    <span class="taka-symbol">৳</span> {{ number_format($payment->amount) }}
                                </div>
                            </div>
                            <form id="manual-payment-form" action="{{ route('manual_payment.submit', $payment->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mb-2">
                                    <label class="form-label">Select Payment Gateway <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap justify-content-start" id="gateway-options">
                                        @foreach($gateways as $gateway)
                                            <label class="gateway-option" data-type="{{ $gateway->type }}" data-color="{{$gateway->color}}" data-details="{{ htmlentities($gateway->details) }}">
                                                <input type="radio" name="gateway_id" value="{{ $gateway->id }}">
                                                @if($gateway->logo)
                                                    <img src="{{ asset('uploads/'.$gateway->logo) }}" alt="{{ $gateway->name }}">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width:80px; height:80px; border-radius:10px;">
                                                        No Logo
                                                    </div>
                                                @endif
                                                <div class="small text-truncate" style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $gateway->name }}</div>
                                                <div class="tick">✔</div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div id="gateway-details" class="alert alert-info d-none text-light"></div>

                                <div class="row mt-3" id="mobile-fields" style="display: none;">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sender Number <span class="text-danger">*</span></label>
                                        <input type="text" name="sender_number" class="form-control" placeholder="01XXXXXXXXX">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                        <input type="text" name="transaction_id" class="form-control" placeholder="e.g. 9XYZ123">
                                    </div>
                                </div>

                                <div class="row mt-3" id="bank-fields" style="display: none;">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Upload Payment Slip/File <span class="text-danger">*</span></label>
                                        <input type="file" name="transaction_file" class="form-control">
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <button type="submit" class="btn btn-success w-100">
                                        Submit Payment Info
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('custom_css')
    <style>
        ul.instructions {
            font-size: 1.12rem; /* 18px এর কাছাকাছি, ভালো পড়ার সাইজ */
            line-height: 1.3;    /* লাইন গ্যাপ একটু বাড়িয়ে পড়া সহজ করতে */
            color: #333;         /* ডার্ক গ্রে কালার, প্রফেশনাল লুক */
            padding-left: 1.25rem; /* লিস্ট আইটেমের বুলেট দেখানোর জায়গা */
        }

        ul.instructions li {
            color:white;
            padding-top: 0.5rem; /* প্রতিটি আইটেমের নিচে একটু স্পেস */
            padding-bottom: 0.5rem; /* প্রতিটি আইটেমের নিচে একটু স্পেস */
            border-bottom: 1px dashed white;
        }

        .amount-display {
            font-size: 2.5rem; /* বড় ফন্ট সাইজ */
            font-weight: 700;
            color: #28a745; /* গ্রীন কালার, চাইলে পরিবর্তন করুন */
            background: #e9f7ef; /* হালকা ব্যাকগ্রাউন্ড */
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            white-space: nowrap;
        }

        .taka-symbol {
            font-size: 2rem; /* টাকা চিহ্ন একটু বড় */
            vertical-align: middle;
            margin-right: 5px;
        }

        /* Responsive: ছোট স্ক্রিনে ফন্ট কমানো */
        @media (max-width: 576px) {
            .amount-display {
                font-size: 1.5rem;
                padding: 8px 15px;
            }
            .taka-symbol {
                font-size: 1.5rem;
            }
        }

        .gateway-option {
            width: 80px;
            height: 80px;
            border: 2px solid #ccc;
            border-radius: 10px;
            text-align: center;
            padding: 0 2px 2px;
            margin: 5px;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
        }
        .gateway-option input[type="radio"] {
            display: none;
        }
        .gateway-option img {
            max-width: 75px;
            max-height: 75px;
            margin-bottom: 2px;
        }
        .gateway-option.selected {
            border-color: #198754;
            background-color: #e7f7ee;
        }
        .tick {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #198754;
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        .gateway-option.selected .tick {
            display: flex;
        }
    </style>
@endsection
@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gatewayOptions = document.querySelectorAll('.gateway-option');
            const detailsBox = document.getElementById('gateway-details');
            const mobileFields = document.getElementById('mobile-fields');
            const bankFields = document.getElementById('bank-fields');

            gatewayOptions.forEach(option => {
                option.addEventListener('click', function () {
                    gatewayOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;

                    const type = this.getAttribute('data-type');
                    const details = this.getAttribute('data-details');
                    const color = this.getAttribute('data-color');

                    if (details) {
                        detailsBox.innerHTML = `${decodeHTMLEntities(details)}`;
                        detailsBox.style.backgroundColor = color;
                        detailsBox.style.borderColor = color;
                        detailsBox.classList.remove('d-none');
                    } else {
                        detailsBox.classList.add('d-none');
                    }

                    if (type === 'mobile') {
                        mobileFields.style.display = 'flex';
                        bankFields.style.display = 'none';
                    } else if (type === 'bank') {
                        bankFields.style.display = 'flex';
                        mobileFields.style.display = 'none';
                    } else {
                        mobileFields.style.display = 'none';
                        bankFields.style.display = 'none';
                    }
                });
            });

            function decodeHTMLEntities(text) {
                const textarea = document.createElement('textarea');
                textarea.innerHTML = text;
                return textarea.value;
            }
        });
    </script>
@endsection

