@extends('layouts.master')

@section('content')

    <section class="ptb-75">
        <div class="container ">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-5 order-1 order-md-2">
                    <div class="card shadow-sm rounded-4 border">
                        <div class="card-body p-4 ">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold">আবার স্বাগতম</h4>
                                <p class="text-muted mb-0">আপনার একাউন্ট লগইন করুন</p>
                            </div>

                            <form id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">ফোন, ইমেইল অথবা একাউন্ট  আইডি</label>
                                    <input type="text" name="login" class="form-control" placeholder="লগইন তথ্য দিন">
                                </div>

                                <div class="mb-3 position-relative">
                                    <label class="form-label">পাসওয়ার্ড</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="passwordInput" placeholder="পাসওয়ার্ড">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="ri-eye-line " id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    <a href="{{route('otp_login')}}" class="mt-1 text-end d-block">OTP দিয়ে লগইন করুন</a>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary rounded-pill">লগইন করুন</button>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('password.reset') }}" class="text-decoration-none small text-primary">পাসওয়ার্ড পুনরুদ্ধার করুন!</a>
                                </div>

                                <div class="text-center mt-4">
                                    <span class="text-muted">একাউন্ট নেই?</span>
                                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">একাউন্ট  খুলুন</a>
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
@endsection
@section('custom_js')
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('ajax.login') }}",
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: function () {
                    Swal.fire({
                        title: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'লগইন সফল হয়েছে',
                        text: 'রিডাইরেক্ট করা হচ্ছে...',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    if(res.notfound){
                        Swal.fire({
                            html: `
                        <div class="text-center">
                            <img src="/confusedman.png" alt="Image" style="max-width:100px; margin-bottom: 10px;">
                            <div style="font-size: 20px; font-weight: bold; color: #e63946;">${res?.login ?? ''} <i class="ri-pencil-line"></i></div>
                            <div class="fw-bolder" style="margin-top: 10px; font-size: 16px;">
                                আপনার দেওয়া তথ্যে কোন একাউন্ট নেই,<br> নতুন একাউন্ট খুলতে চান?
                            </div>
                        </div>
                    `,
                            showCancelButton: true,
                            confirmButtonText: `<i class="ri-user-add-fill me-1"></i> নতুন একাউন্ট খুলুন`,
                            cancelButtonText: `<i class="ri-time-line me-1"></i> আবার চেষ্টা করুন`,
                            confirmButtonColor: '#f9a825',
                            cancelButtonColor: '#ccc',
                            customClass: {
                                confirmButton: 'rounded px-4 py-2 mx-1 border-0',
                                cancelButton: 'rounded px-4 py-2 mx-1 border-0'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // নতুন একাউন্টে রিডাইরেক্ট বা অ্যাকশন
                                window.location.href = '/register';
                            }
                        });
                    }else if (res.wrongPassword) {
                        Swal.fire({
                            html: `
                        <div class="text-center">
                            <img src="/confusedman.png" alt="Image" style="max-width:100px; margin-bottom: 10px;">
                            <div style="font-size: 20px; font-weight: bold; color: #e63946;">${res?.login ?? ''} <i class="ri-lock-password-line"></i></div>
                            <div class="fw-bolder" style="margin-top: 10px; font-size: 16px;">
                                ভুল পাসওয়ার্ড!<br> অনুগ্রহ করে আবার চেষ্টা করুন।
                            </div>
                        </div>
                    `,
                            confirmButtonText: `<i class="ri-arrow-right-line m-1"></i> আবার চেষ্টা করুন`,
                            confirmButtonColor: '#f9a825',
                            customClass: {
                                confirmButton: 'rounded px-4 py-2 mx-1 border-0'
                            },
                            buttonsStyling: false
                        });
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: res?.title ?? 'লগইন ব্যর্থ হয়েছে',
                            text: res?.message ?? 'অনুগ্রহ করে তথ্য পরীক্ষা করুন',
                        });
                    }



                }
            });
        });

        $('#togglePassword').on('click', function () {
            const passwordInput = $('#passwordInput');
            const icon = $('#toggleIcon');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            icon.toggleClass('ri-eye-line ri-eye-off-line');
        });
    </script>
@endsection

