@extends('layouts.master')

@section('content')
    <section class="position-relative overflow-hidden" style="z-index:1;">
        <div class="particle-canvas-5 position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
            <canvas style="display: block; background: rgba(255,255,255,0);" width="100%" height="100%"></canvas>
        </div>
        <div class="form_container bg-white shadow rounded my-5 py-3 position-relative" style="z-index: 1;">
            <form action="{{route('number_purchase_submit',['id'=>$ipNumber->id])}}" method="post"
                  enctype="multipart/form-data" id="ipForm">
                @csrf
                <p class="h3 fw-bold text-center mt-3">Apply For IP Number <br>
                    ({{$ipNumber->number}})
                </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="radio-group">
                    <label>
                        <input type="radio" id="hide" name="account_type" value="personal" checked/>
                        Personal
                    </label>
                    <label for="business">
                        <input type="radio" id="show" name="account_type" value="business"/>
                        <span>Business</span>
                    </label>
                </div>
                <div class="px-4 d-flex justify-content-center align-items-center">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <label for="your_name"><span class="text-danger">*</span> Your Name</label>
                                <input type="text" name="your_name" id="your_name" placeholder=" Your Name" value=""
                                       required autofocus/>
                            </div>

                            <div id="show_hidden" class="input-group">
                                <label for="company_name"><span class="text-danger">*</span> Company Name</label>
                                <input type="text" name="company_name" id="company_name" placeholder=" Company Name"
                                       value=""/>
                            </div>

                            <div class="input-group">
                                <label for="email"><span class="text-danger">*</span> Your Email</label>
                                <input type="email" name="email" id="email" placeholder=" Your Email" value=""
                                       required/>
                            </div>

                            <div class="mb-1">
                                <label for="phone"><span class="text-danger">*</span> Phone Number</label>
                                <div class="input-group">
                                    <select class="form-control" name="phone_country_code" required
                                            style="max-width: 55px;">
                                        @foreach($countries as $country)
                                            <option value="{{$country->phone_code}}">{{$country->phone_code}}
                                                ({{$country->name}})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control" type="text" name="phone" id="phone"
                                           placeholder=" 017XXXXXXXX" value=""/>
                                </div>
                            </div>

                            <div class="mb-1 mt-3">
                                <label for="whatsapp_number">WhatsApp Number</label>
                                <div class="input-group">
                                    <select class="form-control" name="whatsapp_country_code" required
                                            style="max-width: 55px;">
                                        @foreach($countries as $country)
                                            <option value="{{$country->phone_code}}">{{$country->phone_code}}
                                                ({{$country->name}})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control" type="text" name="whatsapp_number" id="whatsapp_number"
                                           placeholder=" 017XXXXXXXX" value=""/>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="ip_number"> IP Number</label>
                                <input type="text" value="{{$ipNumber->number}}" readonly name="ip_number"
                                       id="ip_number" placeholder="096XXXXXXXX"/>
                            </div>

                            <div class="input-group" id="enather_ip_number">
                                <label for="ip_number">Another IP Number <span
                                        style="font-size: 12px;">(Optional)</span></label>
                                <input type="text" name="enather_ip_number" placeholder="096XXXXXXXX"/>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="upload-section">
                                <label class="upload-label"><span class="text-danger">*</span> Upload NID Front</label>
                                <div class="upload-box d-inline-block">
                                    <i class="fas fa-camera camera-icon" data-target="#nid-front-upload"></i>
                                    <img id="nid-front-preview" class="upload-icon w-100 h-100 img-fluid rounded"
                                         src="{{asset('website/img/nid-front.png')}}" alt="Front Preview"/>
                                    <input id="nid-front-upload" name="nid_font_image" type="file" accept="image/*"
                                           required/>
                                </div>
                            </div>

                            <div class="upload-section">
                                <label class="upload-label mt-3"><span class="text-danger">*</span> Upload NID
                                    Back</label>
                                <div class="upload-box d-inline-block">
                                    <i class="fas fa-camera camera-icon" data-target="#nid-back-upload"></i>
                                    <img id="nid-back-preview" class="upload-icon w-100 h-100 img-fluid rounded"
                                         src="{{asset('website/img/nid-back.jpg')}}"
                                         alt="Back Preview"/>
                                    <input id="nid-back-upload" name="nid_back_image" type="file" accept="image/*"
                                           required/>
                                </div>
                            </div>

                            <div class="upload-section" id="trade_license">
                                <label class="upload-label mt-3"><span class="text-danger">*</span> Trade
                                    License</label>
                                <div class="upload-box d-inline-block">
                                    <i class="fas fa-camera camera-icon" data-target="#trade-license"></i>
                                    <img id="trade-license-preview" class="upload-icon w-100 h-100 img-fluid rounded"
                                         src="{{asset('website/img/trade-lainance.jpg')}}"
                                         alt="Trade License"/>
                                    <input id="trade-license" name="trade_license" type="file" accept="image/*"/>
                                </div>
                            </div>

                            <div class="upload-section">
                                <label class="upload-label mt-3"><span class="text-danger">*</span> Photo or Selfie
                                </label>
                                <div class="upload-box d-inline-block text-center">
                                    <i class="fas fa-camera camera-icon" data-target="#selfie-photo"></i>
                                    <img id="selfie-photo-preview" class="upload-icon w-50 h-100 img-fluid rounded"
                                         src="{{asset('website/img/profile.jpg')}}"
                                         alt="Back Preview"/>
                                    <input id="selfie-photo" name="selfie_photo" type="file" accept="image/*" required/>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-center text-primary mb-3">Select Payment Method</h6>
                        <div class="radio-group">
                            <label>
                                <input type="radio"  name="payment_method" value="automatic" checked/>
                                Automatic
                            </label>
                            <label for="business">
                                <input type="radio" name="payment_method" value="manual"/>
                                <span>Manual</span>
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="submit-btn w-50 my-4" id="submitBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection
@section('custom_css')
    <style>
        .form_container {
            max-width: 500px;
            margin: auto;
        }

        .radio-group {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            margin: 20px auto;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            cursor: pointer;
        }

        input[type="radio"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid #00875A;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
        }

        input[type="radio"]:checked {
            background-color: #0B4D2E;
        }

        input[type="radio"]:checked::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        label[for="business"] {
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 15px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
        }

        input[type="text"], input[type="email"], input[type="date"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid rgb(0, 0, 0);
            border-radius: 6px;
            outline: none;
            font-size: 14px;
            color: #0B4D2E;
        }

        input::placeholder {
            color: rgb(169, 172, 171);
        }

        .upload-section {
            margin: 0;
        }


        .upload-label {
            white-space: nowrap;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
            display: block;
            font-size: 14px; /* দরকার হলে ছোট করে দিন */
            margin-right: 5px;
        }


        .upload-box {
            background: #ccc;
            border-radius: 8px;
            height: 100px;
            width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
        }

        .upload-box input[type="file"] {
            opacity: 0;
            width: 200px;
            height: 100px;
            position: absolute;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 48px;
            color: black;
            pointer-events: none;
        }

        .submit-btn {
            background-color: #0B4D2E;
            color: white;
            border: none;
            padding: 8px 0;
            width: 100%;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .submit-btn:disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        #show_hidden, #trade_license, #enather_ip_number {
            display: none;
        }

        .camera-icon {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background: white;
            color: red;
            border-radius: 50%;
            padding: 6px;
            font-size: 18px;
            z-index: 2;
            cursor: pointer;
        }

        @media screen and (max-width: 767px) {
            .upload-section {
                margin: 0 auto !important;
                justify-content: center !important;
                text-align: center
            }
        }
    </style>
@endsection
@section('custom_js')
    <script>
        $(document).ready(function () {
            // Initialize form validation
            function toggleBusinessFields() {
                const isBusiness = $('input[name="account_type"]:checked').val() === 'business';
                $('#show_hidden').toggle(isBusiness);
                $('#trade_license').toggle(isBusiness);
                $('#enather_ip_number').toggle(isBusiness);

                // Toggle required attribute
                $('#company_name').prop('required', isBusiness);
                $('#trade-license').prop('required', isBusiness);

                // Re-check form validity after toggling fields
                setTimeout(checkFormValidity, 100);
            }

            function previewImage(inputId, imgId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(imgId);

                input.addEventListener('change', function () {
                    const file = input.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            checkFormValidity(); // Check validity after image loads
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert("Please upload a valid image file.");
                        input.value = "";
                        preview.src = "";
                        checkFormValidity();
                    }
                });
            }

            // Improved checkFormValidity function
            function checkFormValidity() {
                let isValid = true;

                // Check all visible required fields
                $('input[required]:visible').each(function () {
                    if (!$(this).val()) {
                        isValid = false;
                        return false; // exit loop early
                    }
                });

                // Special check for file inputs
                $('input[type="file"][required]').each(function () {
                    if (!$(this).val()) {
                        isValid = false;
                        return false;
                    }
                });

                $('#submitBtn').prop('disabled', !isValid);
            }

            // Initialize event listeners
            $('input[name="account_type"]').change(function () {
                toggleBusinessFields();
                checkFormValidity();
            });

            // Set up image previews
            previewImage('nid-front-upload', 'nid-front-preview');
            previewImage('nid-back-upload', 'nid-back-preview');
            previewImage('selfie-photo', 'selfie-photo-preview');
            previewImage('trade-license', 'trade-license-preview');

            // Set up camera icon click handlers
            $('.camera-icon').on('click', function () {
                const targetInput = $(this).data('target');
                $(targetInput).trigger('click');
            });

            // Check form validity on any input change
            $(document).on('input change', 'input, select', function () {
                checkFormValidity();
            });


            // Initial check
            toggleBusinessFields();
            checkFormValidity();
        });
    </script>

@endsection

