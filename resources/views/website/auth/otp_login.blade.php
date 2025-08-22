@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->
    @include('website.partials.page_header',['title'=>'Login'])
    <!-- End Page Title Area -->
    <section class="ptb-75">
        <div class="container ">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-5 order-1 order-md-2">
                    <div class="card shadow-sm rounded-4 border">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 text-center">OTP Login</h4>

                            <form id="requestOtpForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Phone / Email / User ID</label>
                                    <input type="text" name="login" class="form-control" placeholder="Enter your phone/email/user ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill">Send OTP <i class="ri-send-plane-line ms-2"></i></button>
                            </form>

                            <form id="verifyOtpForm" style="display:none;">
                                @csrf
                                <div class="mb-3 mt-3">
                                    <label class="form-label">Enter OTP</label>
                                    <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                                </div>

                                <button type="submit" class="btn btn-success w-100 rounded-pill">Verify OTP <i class="ri-check-line ms-2"></i></button>

                                <div class="text-center mt-3">
                                    <button type="button" id="resendOtpBtn" class="btn btn-link p-0" disabled>
                                        <i class="ri-refresh-line"></i> Resend OTP (2:00)
                                    </button>
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
        $(function() {
            let resendTimer = null;
            let resendSeconds = 120;
            let maxResends = 3;
            let resendCount = 0;
            let loginValue = '';

            function startResendTimer() {
                $('#resendOtpBtn').prop('disabled', true);
                resendSeconds = 120;
                $('#resendOtpBtn').text(`Resend OTP (2:00)`);

                resendTimer = setInterval(() => {
                    resendSeconds--;
                    let minutes = Math.floor(resendSeconds / 60);
                    let seconds = resendSeconds % 60;
                    let display = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    $('#resendOtpBtn').text(`Resend OTP (${display})`);

                    if (resendSeconds <= 0) {
                        clearInterval(resendTimer);
                        $('#resendOtpBtn').prop('disabled', false).text('Resend OTP');
                    }
                }, 1000);
            }

            $('#requestOtpForm').on('submit', function(e) {
                e.preventDefault();
                if (resendCount >= maxResends) {
                    Swal.fire('Limit Reached', 'You have reached maximum OTP requests for today.', 'warning');
                    return;
                }

                loginValue = $(this).find('input[name="login"]').val();

                $.ajax({
                    url: "{{ route('ajax.otp_send') }}",
                    method: 'POST',
                    data: {login: loginValue, _token: '{{ csrf_token() }}'},
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Sending OTP...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });
                    },
                    success: (res) => {
                        Swal.close();
                        resendCount++;
                        startResendTimer();
                        $('#requestOtpForm').hide();
                        $('#verifyOtpForm').show();
                        Swal.fire('OTP Sent', 'Please check your phone/email for OTP.', 'success');
                    },
                    error: (xhr) => {
                        Swal.close();
                        let message = xhr.responseJSON?.message || 'Failed to send OTP.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            $('#verifyOtpForm').on('submit', function(e) {
                e.preventDefault();

                let otp = $(this).find('input[name="otp"]').val();

                $.ajax({
                    url: "{{ route('ajax.otp_verify') }}",
                    method: 'POST',
                    data: {login: loginValue, otp: otp, _token: '{{ csrf_token() }}'},
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Verifying OTP...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });
                    },
                    success: (res) => {
                        Swal.close();
                        Swal.fire('Login Successful', 'Redirecting...', 'success').then(() => {
                            window.location.href = res.redirect;
                        });
                    },
                    error: (xhr) => {
                        Swal.close();
                        let message = xhr.responseJSON?.message || 'Invalid OTP.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            });

            $('#resendOtpBtn').on('click', function() {
                if (resendCount >= maxResends) {
                    Swal.fire('Limit Reached', 'You have reached maximum OTP requests for today.', 'warning');
                    return;
                }

                $.ajax({
                    url: "{{ route('ajax.otp_send') }}",
                    method: 'POST',
                    data: {login: loginValue, _token: '{{ csrf_token() }}'},
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Resending OTP...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });
                    },
                    success: (res) => {
                        Swal.close();
                        resendCount++;
                        startResendTimer();
                        Swal.fire('OTP Sent', 'Please check your phone/email for OTP.', 'success');
                    },
                    error: (xhr) => {
                        Swal.close();
                        let message = xhr.responseJSON?.message || 'Failed to resend OTP.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            });
        });
    </script>
@endsection

