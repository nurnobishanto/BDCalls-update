@extends('layouts.master')

@section('content')
    <!-- End Page Title Area -->
    <section>
        <div class="text-center mb-3 mt-4 px-3">
            <p class="h3 fw-bold">Choose Your Best Bundle </p>
        </div>

        <div class="row m-0 p-0 col-10 mx-auto">
            @foreach($bundles as $bundle)
                <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center">
                    <div class="card-custom mb-4 pb-1">
                        <div class="p-1"
                             style="background-color: #4747F8; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            <h5 class="text-center text-white mb-2 fw-bold mt-3"> {{$bundle->title}}
                                ({{bn_number($bundle->minutes)}} মিনিট)</h5>
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
                            <button type="button" class="btn-cancel fw-bold order-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-id="{{$bundle->id}}">অর্ডার করুন
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">আপনি ব্যান্ডেল অর্ডার করতে যাচ্ছেন।</h1>
                    <button type="button" class="btn-close btn-red" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="order-form" action="https://bdcalls.com/bundleorder" method="post"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="fH7yCLIvJQotO8UxXzHauBZAUzKING8ZAmacLHV4"
                               autocomplete="off">
                        <div class="div1">
                            <input type="hidden" id="bundle_id" name="bundle_id">
                            <input type="hidden" id="payment_method" name="payment_method" required>

                            <div class="mb-3">
                                <label for="numberSelect" class="form-label">আপনার আইপি নম্বর দিন</label>
                                <input type="number" name="number" class="form-control" placeholder="096XXXXXXXX">
                            </div>

                            <p class="h6 fw-bold">Payment Option</p>


                            <p class="m-0 p-0 mt-3 mb-2 fw-bold"><i class="fa-solid fa-building-columns"></i> Banks</p>
                            <div class="payment-options d-flex flex-wrap">
                                <img class="payment-img" src="https://bdcalls.com/images/brac_bank.png"
                                     data-method="Brac bank"
                                     alt="Brac bank">
                                <img class="payment-img" src="https://bdcalls.com/images/DBBL.jpg"
                                     data-method="DBBL Bank" alt="DBBL Bank">
                                <img class="payment-img" src="https://bdcalls.com/images/city.jpg"
                                     data-method="City Bank" alt="City Bank">
                                <img class="payment-img" src="https://bdcalls.com/images/ibbl.jpg"
                                     data-method="Ibbl Bank" alt="Ibbl Bank">
                                <img class="payment-img" src="https://bdcalls.com/images/trustbank.jpg"
                                     data-method="Trust bank"
                                     alt="Trust bank">
                                <img class="payment-img mt-2" src="https://bdcalls.com/images/asiabank.jpg"
                                     data-method="Asia Bank"
                                     alt="Asia Bank">
                                <img class="payment-img mt-2" src="https://bdcalls.com/images/ebl.jpg"
                                     data-method="Ebl Bank"
                                     alt="Ebl Bank">
                                <img class="payment-img mt-2" src="https://bdcalls.com/images/onebank.jpg"
                                     data-method="One Bank"
                                     alt="One Bank">
                                <img class="payment-img mt-2" src="https://bdcalls.com/images/ucbbank.jpg"
                                     data-method="Ucb Bank"
                                     alt="Ucb Bank">
                                <img class="payment-img mt-2" src="https://bdcalls.com/images/mtbbank.jpg"
                                     data-method="Mtb Bank"
                                     alt="Mtb Bank">
                            </div>


                            <button class="btn btn-primary mt-3" id="proceed-to-payment">Payment</button>

                        </div>
                        <div class="div2" style="display: none; margin-top: -78px;">
                            <header class="headerdiv2 position-relative">


                                <!-- ✅ Top-right positioned close icon -->
                                <button type="button" id="closeDiv2Icon" aria-label="Close"
                                        class="btn position-absolute top-0 end-0 me-2 mt-1 p-0 text-white fs-3"
                                        style="background: none; border: none;">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </header>
                            <div class=" mb-3 "
                                 style="background-color:white; width: 100%; padding: 20px 50px; margin-top: -13px; z-index: 1;">
                                <p class="h6 fw-bold text-dark text-center" style="line-height: 25px;"><b
                                        class="h4 fw-bold"> মূল্য: <span id="priceBox"></span></b> টাকা</p>

                            </div>


                            <div class="d-flex justify-content-between">
                                <button id="submitBtndiv2" class="btn btn-primary mt-3">Submit</button>
                                <button type="button" class="btn btn-info mt-3" id="closeDiv2Btn" aria-label="Close">
                                    Cancel
                                </button>

                            </div>
                        </div>
                        <div class="div3" style="display: none;">


                            <div class="container-sm my-3 p-0 border border-0">
                                <header class="headerdiv3">
                                    <span id="selectedBankName"></span>
                                    <button type="button" aria-label="Close"
                                            class="btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 text-white fs-3"
                                            style="background:none; border:none;">
                                        <i class="fas fa-times text-danger"></i>
                                    </button>
                                </header>

                                <div class="p-3">
                                    <div class="copy-container">
                                        <div class="copy-labels">
                                            <span>Bank Name</span>
                                            <span>:</span>
                                            <strong class="bank-name">Loading...</strong>
                                        </div>
                                        <button type="button" class="copy-btn" data-copy=".bank-name"
                                                aria-label="Copy Bank Name">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>

                                    <div class="copy-container">
                                        <div class="copy-labels">
                                            <span>Account Name</span>
                                            <span>:</span>
                                            <strong class="account-name">Loading...</strong>
                                        </div>
                                        <button type="button" class="copy-btn" data-copy=".account-name"
                                                aria-label="Copy Account Name">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>

                                    <div class="copy-container">
                                        <div class="copy-labels">
                                            <span>Account No</span>
                                            <span>:</span>
                                            <strong class="account-no">Loading...</strong>
                                        </div>
                                        <button type="button" class="copy-btn" data-copy=".account-no"
                                                aria-label="Copy Account Number">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>

                                    <div class="copy-container">
                                        <div class="copy-labels">
                                            <span>Branch Name</span>
                                            <span>:</span>
                                            <strong class="branch-name">Loading...</strong>
                                        </div>
                                        <button type="button" class="copy-btn" data-copy=".branch-name"
                                                aria-label="Copy Branch Name">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>

                                    <hr/>

                                    <p class="d-flex align-items-start gap-2 info-text">
                                        <i class="fas fa-info-circle mt-1"></i>
                                        <span style="font-size:17px">Please Pay <span
                                                id="priceBox3"></span> taka.</span>
                                    </p>

                                    <div class="text-center my-4">
                                        <button type="button" class="upload-btn" id="uploadBtn">
                                            <i class="far fa-image"></i> Upload Payment Proof
                                        </button>
                                        <input type="file" class="form-control mt-2 d-none" id="screenshorts_div3"
                                               name="screenshorts" accept="image/*">
                                    </div>

                                    <img id="previewImage3" src="#" alt="Image Preview" class="img-thumbnail mb-2"
                                         style="display: none; max-height: 150px; margin: 0 auto;">


                                    <div class="d-flex justify-content-between">
                                        <button id="submitBtn" class="btn btn-primary mt-3">Submit</button>
                                        <button type="button" class="btn btn-info mt-3 " id="selectedBankName"
                                                aria-label="Close">Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

        .active-label {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 14px;
            font-weight: 600;
            color: #4a4a4a;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .active-label .dot {
            width: 0.5rem;
            height: 0.5rem;
            background-color: #f7c948;
            border-radius: 50%;
            display: inline-block;
        }

        .price {
            font-size: 14px;
            font-weight: 600;
            color: #4a4a4a;
        }

        .price-sub {
            font-size: 14px;
            color: #6b6b6b;
        }

        .description {
            font-size: 14px;
            color: #4a4a4a;
            margin-bottom: 1.5rem;
            text-align: center;
            line-height: 1.2;
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

        .btn-cancel {
            background-color: #045630 !important;
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
            background-color: #4747F8 !important;
        }

        .right-border {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 0.75rem;
            border-left: none;
            border: 1px solid #d6cfa7;
            border-radius: 0 1.5rem 1.5rem 0;
            pointer-events: none;
        }

        .payment-img {
            width: 80px;
            border: 1px solid #ccc;
            padding: 0px 9px !important;
            margin-left: 5px;
            border-radius: 6px;
            cursor: pointer;
        }

        .payment-img.selected {
            border-color: blue !important;
        }
    </style>
    <style>
        .btn-close.btn-red {
            filter: brightness(0) saturate(100%) invert(24%) sepia(87%) saturate(7482%) hue-rotate(356deg) brightness(94%) contrast(124%);
        }
    </style>
    <style>
        .payment-image-box {
            flex: 1 1 48%;
            padding: 0;
            margin: 0;
        }

        .payment-image-box img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .payment-image-box input[type="file"] {
            width: 100%;
            height: 37px;
        }

        @media (max-width: 768px) {
            .payment-image-box {
                flex: 1 1 100%;
            }
        }
    </style>
    <style>
        .headerdiv3 {
            background-color: #003a82;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
            padding: 20px !important;
            position: relative;
            text-align: center;
            margin-top: -65px;

        }

        .copy-btn {
            background-color: #f0f6fc;
            border-radius: 0.375rem;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            color: #6c757d;
        }

        .copy-btn:hover {
            color: #212529;
            background-color: #e9ecef;
        }

        .info-text {
            font-size: 0.9rem;
        }

        .upload-btn {
            border: 1px solid #5c5ecf;
            color: #5c5ecf;
            border-radius: 0.375rem;
            font-size: 0.9rem;
            padding: 0.375rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .upload-btn:hover {
            background-color: #f0f0ff;
            color: #5c5ecf;
        }

        .submit-btn {
            background-color: #4a6ea9;
            color: white;
            font-size: 1.125rem;
            font-weight: 400;
            border-radius: 0.375rem;
            padding: 0.75rem 0;
            border: none;
        }

        .cancel-btn {
            font-weight: 600;
            font-size: 1.125rem;
            border-radius: 0.375rem;
            border: 1px solid black;
            color: black;
            background-color: white;
            padding: 0.75rem 0;
        }

        .copy-container {
            background-color: #f0f6fc;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .copy-labels {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #495057;
            font-size: 1rem;
        }

        .copy-labels strong {
            font-weight: 600;
        }

        hr {
            border-color: #adb5bd;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endsection
@section('custom_js')
    <script>
        // Click the hidden file input when button is clicked
        document.getElementById('uploadBtn').addEventListener('click', function () {
            document.getElementById('screenshorts').click();
        });

        // Show image preview when file is selected
        document.getElementById('screenshorts').addEventListener('change', function (e) {
            const previewImage = document.getElementById('previewImage1');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                    previewImage.style.display = 'block';
                }

                reader.readAsDataURL(file);
            } else {
                previewImage.style.display = 'none';
                previewImage.src = '#';
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            const banks = [{
                "id": 1,
                "bank_name": "Brac Bank",
                "account_name": "GREENSHOP",
                "account_no": "2401204829782001",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T11:05:35.000000Z"
            }, {
                "id": 2,
                "bank_name": "DUTCH BANGLA BANK",
                "account_name": "NAZIM UDDIN",
                "account_no": "1631570004758",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T10:57:48.000000Z"
            }, {
                "id": 3,
                "bank_name": "THE CITY BANK",
                "account_name": "NAZIM UDDIN",
                "account_no": "2303143124001",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T10:56:10.000000Z"
            }, {
                "id": 4,
                "bank_name": "ibbl",
                "account_name": "NAZIM UDDIN",
                "account_no": "20501250204219315",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T11:11:23.000000Z"
            }, {
                "id": 5,
                "bank_name": "TRUST BANK",
                "account_name": "NAZIM UDDIN",
                "account_no": "0002-0310902144",
                "brance_name": "PRINCIPAL BRANCH",
                "created_at": null,
                "updated_at": "2025-08-02T10:59:56.000000Z"
            }, {
                "id": 6,
                "bank_name": "BANK ASIA",
                "account_name": "NAZIM UDDIN",
                "account_no": "1083441245099",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T10:55:31.000000Z"
            }, {
                "id": 7,
                "bank_name": "Eastern Bank (EBL)",
                "account_name": "NAZIM UDDIN",
                "account_no": "1951440154586",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T10:57:16.000000Z"
            }, {
                "id": 8,
                "bank_name": "One Bank Limited (OBL)",
                "account_name": "NAZIM UDDIN",
                "account_no": "0122050037994",
                "brance_name": "Kawran Bazar branch",
                "created_at": null,
                "updated_at": "2025-08-02T11:00:42.000000Z"
            }, {
                "id": 9,
                "bank_name": "UCB BANK",
                "account_name": "NAZIM UDDIN",
                "account_no": "0373222000000155",
                "brance_name": "JASHORE",
                "created_at": null,
                "updated_at": "2025-08-02T11:01:17.000000Z"
            }, {
                "id": 10,
                "bank_name": "Mtb Bank",
                "account_name": "UPCOMING",
                "account_no": "UPCOMING",
                "brance_name": "UPCOMING",
                "created_at": null,
                "updated_at": "2025-08-02T11:01:40.000000Z"
            }];

            const paymentImageDivs = {
                'bKash': $('#bkashImage')[0],
                'Nagad': $('#nagadImage')[0],
                'Rocket': $('#rocketImage')[0],
                'Bangla Qr': $('#banglaqrImage')[0],
                'Brac bank': $('#BracbankImage')[0],
                'DBBL Bank': $('#DbblbankImage')[0],
                'City Bank': $('#CityBankImage')[0],
                'Ibbl Bank': $('#IbblBankImage')[0],
                'Trust bank': $('#TrustbankImage')[0],
                'Asia Bank': $('#AsiaBankImage')[0],
                'Ebl Bank': $('#EblBankImage')[0],
                'One Bank': $('#OneBankImage')[0],
                'Ucb Bank': $('#UcbBankImage')[0],
                'Mtb Bank': $('#MtbBankImage')[0]
            };

            $('.div2, .div3').hide();

            function handleFormInputsVisibility() {
                if ($('.div2').is(':visible')) {
                    $('.div2 input, .div2 select, .div2 textarea, .div2 button').prop('disabled', false);
                    $('.div3 input, .div3 select, .div3 textarea, .div3 button').prop('disabled', true);
                } else if ($('.div3').is(':visible')) {
                    $('.div3 input, .div3 select, .div3 textarea, .div3 button').prop('disabled', false);
                    $('.div2 input, .div2 select, .div2 textarea, .div2 button').prop('disabled', true);
                }
            }

            function selectPaymentMethod(method) {
                $('.payment-img').removeClass('selected');
                $(`.payment-img[data-method="${method}"]`).addClass('selected');
                $('#payment_method').val(method);

                // Show image
                Object.keys(paymentImageDivs).forEach(key => {
                    if (paymentImageDivs[key]) {
                        paymentImageDivs[key].style.display = (key === method) ? 'block' : 'none';
                    }
                });

                // Bank info
                const bankMethods = Object.keys(paymentImageDivs).filter(name => name.toLowerCase().includes("bank"));
                if (bankMethods.includes(method)) {
                    const bank = banks.find(b => b.bank_name === method);
                    if (bank) {
                        $('#selectedBankName').text(bank.bank_name);
                        $('.bank-name').text(bank.bank_name);
                        $('.account-name').text(bank.account_name);
                        $('.account-no').text(bank.account_no);
                        $('.branch-name').text(bank.brance_name);
                    }
                }
            }

            $('.payment-img').on('click', function () {
                const method = $(this).data('method');
                selectPaymentMethod(method);
            });

            $('#proceed-to-payment').on('click', function (e) {
                e.preventDefault();

                let numberInput;
                numberInput = $('input[name="number"]').val();

                if (!numberInput || numberInput.trim() === '') {
                    alert("অনুগ্রহ করে আপনার IP নম্বর দিন বা নির্বাচন করুন।");
                    return;
                }

                const paymentMethod = $('#payment_method').val();
                if (!paymentMethod || paymentMethod.trim() === '') {
                    alert("অনুগ্রহ করে একটি পেমেন্ট মেথড নির্বাচন করুন।");
                    return;
                }


                const bankMethods = Object.keys(paymentImageDivs).filter(name => name.toLowerCase().includes("bank"));

                $('.div1, .div2, .div3').hide();

                if (bankMethods.includes(paymentMethod)) {
                    $('.div3').show();
                    $('.modal-header').hide();
                } else {
                    $('.div2').show();
                    $('.modal-header').show();
                }

                handleFormInputsVisibility();
            });

            // Screenshot preview div2
            $('#screenshorts_div2').on('change', function (e) {
                const previewImage = document.getElementById('previewImage2');
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewImage.src = event.target.result;
                        previewImage.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.style.display = 'none';
                    previewImage.src = '#';
                }
            });

            // Screenshot preview div3
            $('#uploadBtn').on('click', function () {
                $('#screenshorts_div3').click();
            });

            $('#screenshorts_div3').on('change', function (e) {
                const previewImage = document.getElementById('previewImage3');
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewImage.src = event.target.result;
                        previewImage.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.style.display = 'none';
                    previewImage.src = '#';
                }
            });

            // Modal open
            const exampleModal = document.getElementById('exampleModal');
            exampleModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const numberId = button.getAttribute('data-number-id');
                const number = button.getAttribute('data-number');

                document.getElementById('loadingSpinner').style.display = 'block';
                $('#bill_due_id, #amount').val('');
                $('#number_id').val(numberId);
                $('#number').val(number);

                fetch(`/get-unpaid-bill/${numberId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loadingSpinner').style.display = 'none';
                        if (data.status === 'success') {
                            $('#bill_due_id').val(data.data.id);
                            $('#amount').val(data.data.amount);
                            $('#priceBox').text(data.data.amount + ' ');
                            $('#priceBox3').text(data.data.amount + ' ');
                        } else {
                            alert("No unpaid bill found for this number.");
                        }
                    })
                    .catch(error => {
                        document.getElementById('loadingSpinner').style.display = 'none';
                        console.error('Error fetching unpaid bill:', error);
                    });

                $('.div1').show();
                $('.div2, .div3').hide();
                $('.payment-img').removeClass('selected');
                $('#payment_method').val('');
                handleFormInputsVisibility();
            });

            $(document).ready(function () {
                // ✅ Copy Button Functionality
                $(document).on('click', '.copy-btn', function (e) {
                    e.preventDefault();

                    const selector = $(this).data('copy');  // e.g. ".bank-name"
                    const target = document.querySelector(selector);

                    if (!target) {
                        alert("কপি করার উপাদান খুঁজে পাওয়া যায়নি!");
                        return;
                    }

                    const text = target.innerText.trim();

                    if (!text || text === 'Loading...') {
                        alert("কপি করার জন্য বৈধ ডেটা পাওয়া যায়নি!");
                        return;
                    }

                    // Modern clipboard API
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => {
                            showCopySuccess($(this));
                        }).catch(() => {
                            fallbackCopy(text, $(this));
                        });
                    } else {
                        fallbackCopy(text, $(this));
                    }
                });

                // ✅ Fallback for insecure (non-HTTPS) or older browsers
                function fallbackCopy(text, $button) {
                    const textarea = $('<textarea>')
                        .val(text)
                        .css({
                            position: 'fixed',
                            top: 0,
                            left: 0,
                            opacity: 0,
                        });

                    $('body').append(textarea);
                    textarea[0].select();

                    try {
                        const success = document.execCommand('copy');
                        if (success) {
                            showCopySuccess($button);
                        } else {
                            alert('কপি ব্যর্থ হয়েছে! ম্যানুয়ালি কপি করুন: ' + text);
                        }
                    } catch (err) {
                        alert('কপি ব্যর্থ হয়েছে! ম্যানুয়ালি কপি করুন: ' + text);
                    }

                    textarea.remove();
                }

                // ✅ Copy Success Icon Change
                function showCopySuccess($button) {
                    const $icon = $button.find('i');
                    $icon.removeClass('fa-copy').addClass('fa-check');
                    setTimeout(() => {
                        $icon.removeClass('fa-check').addClass('fa-copy');
                    }, 2000);
                }
            });

            $(document).ready(function () {
                $('#submitBtndiv2').on('click', function (e) {
                    const file = $('#screenshorts_div2').prop('files')[0];

                    if (!file) {
                        e.preventDefault(); // Stop form submission
                        alert("Please select a file before submitting.");
                    }
                });
            });

            $(document).ready(function () {
                $('#submitBtn').on('click', function (e) {
                    const file = $('#screenshorts_div3').prop('files')[0];

                    if (!file) {
                        e.preventDefault(); // Stop form submission
                        alert("Please select a file before submitting.");
                    }
                });
            });


            $(document).ready(function () {
                $('#closeDiv2Btn, #closeDiv2Icon').on('click', function () {
                    $('.div2').hide();          // div2 লুকাবে
                    $('.div1').show();          // div1 দেখাবে
                    $('.modal-header').show(); // modal header দেখাবে (যদি hide করা থাকে)
                });
            });


            // Cancel div3
            $('.div3 .btn[aria-label="Close"], .div3 .cancel-btn').on('click', function () {
                $('.div3').hide();
                $('.div1').show();
                $('.modal-header').show();
                handleFormInputsVisibility();
            });

            // Live update amount
            $('#amount').on('input', function () {
                const amount = this.value;
                $('#priceBox, #priceBox3').text(amount + ' ');
            });

            // Handle package price display and selection
            const input = document.getElementById('bundle_id');
            const priceBox = document.getElementById('priceBox');

            document.querySelectorAll('.order-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const bundleId = this.getAttribute('data-id');
                    input.value = bundleId;

                    // Ajax request to fetch the price
                    fetch(`/get-bundle-price/${bundleId}`)
                        .then(response => response.json())
                        .then(data => {
                            priceBox.textContent = data.price + ' ';
                            $('#priceBox3').text(data.price + ' '); // ✅ Add this line
                            $('#amount').val(data.price);
                        })

                        .catch(error => {
                            priceBox.textContent = 'Price not found';
                        });

                    $('.div1').show();
                    $('.div2, .div3').hide();
                    $('.payment-img').removeClass('selected');
                    $('#payment_method').val('');
                    handleFormInputsVisibility();
                });
            });
        });
    </script>
@endsection

