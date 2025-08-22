@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->
    @include('website.partials.page_header',['title'=>'Password Reset'])
    <!-- End Page Title Area -->
    <section class="ptb-75">
        <div class="container ">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 order-1 order-md-2">
                    <div class="card shadow-sm rounded-4 border">
                        <div class="card-body p-4">
                            <h4 class="text-center fw-bold mb-3">পাসওয়ার্ড রিসেট করুন</h4>
                            <form id="passwordResetForm">
                                @csrf

                                <div id="step1">
                                    <div class="mb-3">
                                        <label class="form-label">ফোন, ইমেইল অথবা একাউন্ট আইডি</label>
                                        <input type="text" name="login" class="form-control" placeholder="আপনার লগইন তথ্য দিন">
                                    </div>
                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-primary rounded-pill">OTP পাঠান</button>
                                    </div>
                                </div>

                                <div id="step2" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">OTP কোড</label>
                                        <input type="text" name="otp" class="form-control" placeholder="প্রাপ্ত OTP দিন">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">নতুন পাসওয়ার্ড</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPassword" class="form-control" placeholder="নতুন পাসওয়ার্ড দিন">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-success rounded-pill">পাসওয়ার্ড রিসেট করুন</button>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" id="resendOtpBtn" class="btn btn-link text-decoration-none" disabled>
                                            পুনরায় OTP পাঠান (2:00)
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 order-2 order-md-1">
                    <img src="{{asset('website/img/student-login.svg')}}" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
    <script>
        let resendCountdown = 120;
        let interval;

        function startResendCountdown() {
            $('#resendOtpBtn').attr('disabled', true);
            interval = setInterval(() => {
                resendCountdown--;
                $('#resendOtpBtn').text(`পুনরায় OTP পাঠান (${Math.floor(resendCountdown / 60)}:${(resendCountdown % 60).toString().padStart(2, '0')})`);
                if (resendCountdown <= 0) {
                    clearInterval(interval);
                    $('#resendOtpBtn').text('পুনরায় OTP পাঠান').removeAttr('disabled');
                }
            }, 1000);
        }

        $('#passwordResetForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: $('#step1').is(':visible') ? "{{ route('ajax.password_send_otp') }}" : "{{ route('ajax.password_verify_otp') }}",
                method: "POST",
                data: formData,
                beforeSend: () => Swal.fire({title: 'Please wait...', allowOutsideClick: false, didOpen: () => Swal.showLoading()}),
                success: function(res) {
                    Swal.close();
                    if (res.step === 2) {
                        $('#step1').hide();
                        $('#step2').show();
                        startResendCountdown();
                    } else if (res.reset === true) {
                        Swal.fire({icon: 'success', title: 'সফল', text: res.message});
                        window.location.href = "{{ route('login') }}";
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = xhr.responseJSON?.message || 'ত্রুটি ঘটেছে';
                    Swal.fire({icon: 'error', title: 'ব্যর্থ', text: msg});
                }
            });
        });

        $('#resendOtpBtn').on('click', function () {
            let login = $('input[name="login"]').val();
            $.post("{{ route('ajax.password_send_otp') }}", {login, _token: "{{ csrf_token() }}"}, function (res) {
                Swal.fire({icon: 'success', title: 'OTP পাঠানো হয়েছে'});
                resendCountdown = 120;
                startResendCountdown();
            }).fail(function (xhr) {
                let msg = xhr.responseJSON?.message || 'ত্রুটি ঘটেছে';
                Swal.fire({icon: 'error', title: 'ব্যর্থ', text: msg});
            });
        });

        $('#togglePassword').on('click', function () {
            let passwordInput = $('#newPassword');
            let icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });
    </script>
@endsection

