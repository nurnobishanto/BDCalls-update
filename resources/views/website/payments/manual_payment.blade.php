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

                                {{-- Gateway options --}}
                                <div class="mb-2">
                                    <label class="form-label">Select Payment Gateway <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap justify-content-start" id="gateway-options">
                                        @foreach($gateways as $gateway)
                                            <label class="gateway-option"
                                                   data-type="{{ $gateway->type }}"
                                                   data-color="{{ $gateway->color }}"
                                                   data-details="{{ htmlentities($gateway->details) }}"
                                                   data-name="{{ $gateway->name }}"
                                                   data-account-name="{{ $gateway->account_name }}"
                                                   data-account-number="{{ $gateway->number }}"
                                                   data-branch="{{ $gateway->branch }}"
                                                   data-routing="{{ $gateway->routing_no }}">
                                                <input type="radio" name="gateway_id" value="{{ $gateway->id }}">
                                                @if($gateway->logo)
                                                    <img src="{{ asset('uploads/'.$gateway->logo) }}" alt="{{ $gateway->name }}">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width:80px; height:80px; border-radius:10px;">
                                                        No Logo
                                                    </div>
                                                @endif
                                                <div class="small text-truncate">{{ $gateway->name }}</div>
                                                <div class="tick">✔</div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Instructions box --}}
                                <div id="gateway-details" class="alert alert-info d-none text-light"></div>

                                {{-- Mobile fields --}}
                                <div class="row mt-3" id="mobile-fields" style="display:none;">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sender Number <span class="text-danger">*</span></label>
                                        <input type="text" name="sender_number" class="form-control" placeholder="01XXXXXXXXX">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                        <input type="text" name="transaction_id" class="form-control" placeholder="e.g. 9XYZ123">
                                    </div>
                                </div>

                                {{-- Bank fields --}}
                                <div class="row mt-3" id="bank-fields" style="display:none; flex-direction:column; gap:12px;">
                                    <div class="bank-info">
                                        <strong>Gateway Name:</strong> <span id="bank-gateway-name"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" data-target="bank-gateway-name">Copy</button>
                                    </div>
                                    <div class="bank-info">
                                        <strong>Account Name:</strong> <span id="bank-account-name"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" data-target="bank-account-name">Copy</button>
                                    </div>
                                    <div class="bank-info">
                                        <strong>Account Number:</strong> <span id="bank-account-number"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" data-target="bank-account-number">Copy</button>
                                    </div>
                                    <div class="bank-info">
                                        <strong>Branch:</strong> <span id="bank-branch"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" data-target="bank-branch">Copy</button>
                                    </div>
                                    <div class="bank-info">
                                        <strong>Routing No:</strong> <span id="bank-routing"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" data-target="bank-routing">Copy</button>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Upload Payment Slip/File <span class="text-danger">*</span></label>
                                        <input type="file" name="transaction_file" class="form-control">
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <button type="submit" class="btn btn-success w-100">Submit Payment Info</button>
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
        .amount-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: #28a745;
            background: #e9f7ef;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            white-space: nowrap;
        }
        .taka-symbol {
            font-size: 2rem;
            margin-right: 5px;
        }
        .gateway-option {
            width: 80px;
            height: 80px;
            border: 2px solid #ccc;
            border-radius: 10px;
            text-align: center;
            margin: 5px;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
        }
        .gateway-option input[type="radio"] { display: none; }
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
        .gateway-option.selected .tick { display: flex; }
        .bank-info {
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:6px 10px;
            border:1px dashed #ccc;
            border-radius:6px;
            background:#f8f9fa;
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
                        detailsBox.innerHTML = decodeHTMLEntities(details);
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
                        document.getElementById('bank-gateway-name').textContent = this.getAttribute('data-name') || '';
                        document.getElementById('bank-account-name').textContent = this.getAttribute('data-account-name') || '';
                        document.getElementById('bank-account-number').textContent = this.getAttribute('data-account-number') || '';
                        document.getElementById('bank-branch').textContent = this.getAttribute('data-branch') || '';
                        document.getElementById('bank-routing').textContent = this.getAttribute('data-routing') || '';

                        bankFields.style.display = 'flex';
                        mobileFields.style.display = 'none';
                    } else {
                        mobileFields.style.display = 'none';
                        bankFields.style.display = 'none';
                    }
                });
            });

            // Copy button
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('copy-btn')) {
                    const targetId = e.target.getAttribute('data-target');
                    const text = document.getElementById(targetId).textContent.trim();
                    navigator.clipboard.writeText(text).then(() => {
                        e.target.textContent = "Copied!";
                        setTimeout(() => e.target.textContent = "Copy", 1500);
                    });
                }
            });

            function decodeHTMLEntities(text) {
                const textarea = document.createElement('textarea');
                textarea.innerHTML = text;
                return textarea.value;
            }
        });
    </script>
@endsection
