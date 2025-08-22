@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->
    @include('website.partials.page_header',['title'=>'Registration'])
    <!-- End Page Title Area -->
    <section class="ptb-75">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="card shadow-sm rounded-4 border">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <h4 class="fw-bold mt-3">একাউন্ট তৈরি করুন</h4>
                                <p class="text-muted mb-0">সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করুন</p>
                            </div>

                            <form id="registerForm">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">পুরো নাম</label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="আপনার পুরো নাম লিখুন" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">ফোন নম্বর </label>
                                    <input type="number" name="register" class="form-control"
                                           placeholder="ফোন নম্বর দিন" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">পাসওয়ার্ড সেট করুন </label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password"
                                               placeholder="পাসওয়ার্ড লিখুন" required>
                                        <span class="input-group-text" onclick="togglePassword()">
                                            <i class="ri-eye-line" id="toggleIcon"></i>
                                        </span>
                                    </div>
                                </div>


                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary rounded-pill">সাবমিট করুন</button>
                                </div>

                                <div class="text-center">
                                    <span class="text-muted">আগে থেকেই একাউন্ট আছে?</span>
                                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">লগইন
                                        করুন</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                input.type = "password";
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }

        $('#registerForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('ajax.register') }}",
                type: "POST",
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
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'রেজিস্ট্রেশন সফল হয়েছে!',
                        text: 'ড্যাশবোর্ডে নিয়ে যাওয়া হচ্ছে...',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect;
                    });
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: res?.title ?? 'রেজিস্ট্রেশন ব্যর্থ হয়েছে',
                        text: res?.message ?? 'অনুগ্রহ করে আপনার তথ্য যাচাই করুন।',
                    });
                }
            });
        });
    </script>
@endsection

