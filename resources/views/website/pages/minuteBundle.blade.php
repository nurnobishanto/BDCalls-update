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
                                <button type="button" class="btn btn-primary order-btn"
                                        data-bundle-id="{{ $bundle->id }}"
                                        data-bundle-title="{{ $bundle->title }}">অর্ডার করুন
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
    <style>
        .payment-options .option {
            cursor: pointer;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 8px;
            transition: all 0.3s;
        }

        .payment-options .option:hover {
            border-color: #198754;
            background-color: #e7f7ee;
        }

        .payment-options input[type="radio"] {
            cursor: pointer;
        }
    </style>


@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderButtons = document.querySelectorAll('.order-btn');

            orderButtons.forEach(btn => {
                btn.addEventListener('click', async function startOrder() {
                    const bundleId = this.dataset.bundleId;
                    const bundleTitle = this.dataset.bundleTitle;

                    while (true) {
                        // Step 1: Ask user to enter IP number
                        const { value: number } = await Swal.fire({
                            title: 'Enter your IP Number',
                            input: 'text',
                            inputPlaceholder: 'Type your IP number',
                            showCancelButton: true,
                            confirmButtonText: 'Search',
                        });

                        if (!number) return; // Cancel pressed

                        // Step 2: Search IP via Ajax
                        const response = await fetch(`/api/search-ip?number=${number}`);
                        const result = await response.json();

                        if (!result.success) {
                            const retry = await Swal.fire({
                                icon: 'error',
                                title: 'Not Found',
                                text: result.message,
                                showCancelButton: true,
                                confirmButtonText: 'Search Again',
                                cancelButtonText: 'Cancel'
                            });
                            if (!retry.isConfirmed) return;
                            continue;
                        }

                        const ip = result.data;

                        // Step 3: Confirm order with payment method
                        const { value: paymentMethod } = await Swal.fire({
                            title: 'Confirm Order',
                            html: `
                        <p class="mb-0 fs-5">Bundle: <b>${bundleTitle}</b></p>
                        <p class="mb-0 fs-4 text-primary">IP Number: <b>${ip.number}</b></p>
                        <p class="mb-0 fs-6">User Name: <b>${ip.user_name}</b></p>
                        <div class="payment-options d-flex flex-column gap-3">
                            <label class="option d-flex flex-column align-items-start">
                                <span class="option-text mb-1 small">Credit/Debit Card/Bkash/Nagad (Extra Charge 2%)</span>
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="payment_method" value="automatic" checked class="me-2">
                                    <img src="/website/img/automatic.png" style="max-width: 95%">
                                </div>
                            </label>

                            <label class="option d-flex flex-column align-items-start">
                                <span class="option-text mb-1 small">Manual Payment</span>
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="payment_method" value="manual" class="me-2">
                                    <img src="/website/img/manual.png" style="max-width: 95%">
                                </div>
                            </label>

                        </div>
                    `,
                            showCancelButton: true,
                            confirmButtonText: 'Submit Order',
                            preConfirm: () => {
                                const selected = document.querySelector('input[name="payment_method"]:checked');
                                if (!selected) Swal.showValidationMessage('Please select a payment method');
                                return selected.value;
                            }
                        });

                        if (!paymentMethod) return;

                        // Step 4: Submit normal form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/order-minute-bundle`;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        const bundleInput = document.createElement('input');
                        bundleInput.type = 'hidden';
                        bundleInput.name = 'minute_bundle_id';
                        bundleInput.value = bundleId;
                        form.appendChild(bundleInput);

                        const ipInput = document.createElement('input');
                        ipInput.type = 'hidden';
                        ipInput.name = 'user_ip_number_id';
                        ipInput.value = ip.id;
                        form.appendChild(ipInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = 'payment_method';
                        methodInput.value = paymentMethod;
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit(); // Controller will handle redirect
                        break; // exit loop
                    }
                });
            });
        });
    </script>



@endsection

