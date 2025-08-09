@extends('layouts.master')

@section('content')

    <section class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="thank-you-card text-center">
                        <div class="tick"></div>
                        <h2 class="mb-3 fw-bold">Thank You!</h2>
                        <p class="mb-4 fs-5 text-secondary">
                            Your submission has been received successfully.
                            We appreciate your interest and will get back to you shortly.
                        </p>
                        <a href="/" class="btn btn-success btn-lg px-4">Go Back Home</a>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('custom_css')
    <style>

        .thank-you-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgb(0 0 0 / 0.15);
            max-width: 450px;
            padding: 2.5rem 2rem;
            margin: auto;
        }
        .tick {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            border: 4px solid #198754; /* Bootstrap green */
            position: relative;
            animation: scaleUp 0.6s ease forwards;
        }
        .tick::before {
            content: "";
            position: absolute;
            left: 45px;
            top: 15px;
            width: 18px;
            height: 36px;
            border-right: 5px solid #198754;
            border-bottom: 5px solid #198754;
            transform: rotate(45deg);
            transform-origin: left top;
            opacity: 0;
            animation: drawTick 0.4s 0.6s ease forwards;
        }
        @keyframes scaleUp {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes drawTick {
            0% { width: 0; height: 0; opacity: 1; }
            100% { width: 18px; height: 36px; opacity: 1; }
        }
    </style>
@endsection
@section('custom_js')
@endsection

